<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OjtLog;
use Carbon\Carbon;

class OjtLogController extends Controller
{
    public function store(Request $request)
    {
        $profile = auth()->user()->studentProfile;
        $hasCompany = $profile && $profile->company_id !== null;
        $hasAdvisor = $hasCompany && $profile->company->users()->where('role', 'Advisor')->exists();

        if (!$hasCompany) {
            return redirect()->back()->withInput()->withErrors([
                'log_date' => 'You cannot submit logs because you have not been assigned to a company placement yet.'
            ]);
        }

        if (!$hasAdvisor) {
            return redirect()->back()->withInput()->withErrors([
                'log_date' => 'You cannot submit logs because your assigned company does not have a supervisor/advisor account registered yet.'
            ]);
        }

        $validated = $request->validate([
            'log_date' => 'required|date|before_or_equal:today',
            'am_clock_in' => 'required_with:am_clock_out|nullable|date_format:H:i',
            'am_clock_out' => 'nullable|after:am_clock_in|date_format:H:i',
            'pm_clock_in' => 'required_with:pm_clock_out|nullable|date_format:H:i|after:am_clock_out',
            'pm_clock_out' => 'nullable|after:pm_clock_in|date_format:H:i',
            'activity_summary' => 'required|string|min:20',
            'photo_attachment' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $exists = OjtLog::where('user_id', auth()->id())->where('log_date', $request->log_date)->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['log_date' => 'You have already submitted an OJT log for this date.']);
        }

        $photoPath = null;
        if ($request->hasFile('photo_attachment')) {
            $file = $request->file('photo_attachment');
            $fileName = uniqid() . '.jpg';
            $dirPath = storage_path('app/public/ojt_photos');
            
            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0755, true);
            }
            
            $targetPath = $dirPath . '/' . $fileName;
            
            if ($this->compressAndSaveImage($file->getPathname(), $targetPath, 800, 75)) {
                $photoPath = 'ojt_photos/' . $fileName;
            } else {
                // Fallback to direct store if GD compression fails for some reason
                $photoPath = $file->store('ojt_photos', 'public');
            }
        }

        // Calculate hours dynamically based on inputs (Secure backend validation matching frontend human decimal map)
        $totalMinutes = 0;
        
        if ($request->filled('am_clock_in') && $request->filled('am_clock_out')) {
            $amIn = Carbon::createFromFormat('H:i', $request->am_clock_in);
            $amOut = Carbon::createFromFormat('H:i', $request->am_clock_out);
            if ($amOut->lessThan($amIn)) $amOut->addDay(); // Handle cross-midnight shifts
            $totalMinutes += $amIn->diffInMinutes($amOut);
        }

        if ($request->filled('pm_clock_in') && $request->filled('pm_clock_out')) {
            $pmIn = Carbon::createFromFormat('H:i', $request->pm_clock_in);
            $pmOut = Carbon::createFromFormat('H:i', $request->pm_clock_out);
            if ($pmOut->lessThan($pmIn)) $pmOut->addDay();
            $totalMinutes += $pmIn->diffInMinutes($pmOut);
        }

        $ot_minutes = 0;
        if ($request->filled('ot_clock_in') && $request->filled('ot_clock_out')) {
            $ot_in = Carbon::createFromFormat('H:i', $request->ot_clock_in);
            $ot_out = Carbon::createFromFormat('H:i', $request->ot_clock_out);
            
            if ($ot_out->lessThan($ot_in)) $ot_out->addDay();
            $ot_minutes = $ot_in->diffInMinutes($ot_out);
            $totalMinutes += $ot_minutes;
        }

        // Convert minutes to true decimal hours (e.g. 8 hrs 30 mins = 8.50 hrs)
        $totalHours = round($totalMinutes / 60, 2);
        $otHours = round($ot_minutes / 60, 2);

        OjtLog::create([
            'user_id' => auth()->id(),
            'log_date' => $request->log_date,
            'morning_in' => $request->am_clock_in,
            'morning_out' => $request->am_clock_out,
            'afternoon_in' => $request->pm_clock_in,
            'afternoon_out' => $request->pm_clock_out,
            'ot_clock_in' => $request->ot_clock_in,
            'ot_clock_out' => $request->ot_clock_out,
            'ot_duration' => $otHours,
            'hours_rendered' => $totalHours,
            'tasks_performed' => $request->activity_summary,
            'photo_path' => $photoPath,
            'status' => 'Pending',
            'has_overtime' => $request->has('has_overtime')
        ]);

        return redirect()->back()->with('success', 'OJT Shift Logged Successfully!');
    }

    public function index(Request $request)
    {
        $query = OjtLog::where('user_id', auth()->id());

        // 1. Search Query (tasks_performed or remarks)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('tasks_performed', 'like', "%{$search}%")
                  ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        // 2. Status Filter (Approved, Pending, Rejected)
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // 3. Month Filter (YYYY-MM)
        if ($request->filled('month') && $request->input('month') !== 'all') {
            $month = $request->input('month');
            $query->where('log_date', 'like', "{$month}%");
        }

        // Available months for authenticated student
        $logDates = OjtLog::where('user_id', auth()->id())->pluck('log_date');
        $availableMonths = $logDates->map(function ($date) {
            $c = Carbon::parse($date);
            return [
                'value' => $c->format('Y-m'),
                'label' => $c->format('F Y'),
            ];
        })->unique('value')->sortByDesc('value')->values();

        // Fetch the filtered student logs ordered by newest date
        $logs = $query->orderBy('log_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('student.logs', compact('logs', 'availableMonths'));
    }

    public function destroy(OjtLog $ojtLog)
    {
        // Security Check: Ensure this log belongs to the logged-in student
        if ($ojtLog->user_id !== auth()->id()) {
            abort(403);
        }

        // CRITICAL STATUS GUARD TRAP: Only allow deletion if the log is pending
        if (strtoupper($ojtLog->status) !== 'PENDING') {
            return redirect()->back()->with('error', 'You cannot delete a log that has already been approved or rejected by an advisor!');
        }

        // Clean up the associated uploaded photo file from storage if it exists
        if ($ojtLog->photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($ojtLog->photo_path);
        }

        $ojtLog->delete();

        return redirect()->back()->with('success', 'OJT shift log entry has been successfully deleted.');
    }

    public function edit(OjtLog $ojtLog)
    {
        if ($ojtLog->user_id !== auth()->id()) {
            abort(403);
        }

        if (strtoupper($ojtLog->status) !== 'REJECTED') {
            return redirect()->route('student.logs.index')->with('error', 'You can only edit rejected logs.');
        }

        return view('student.edit-log', compact('ojtLog'));
    }

    public function update(Request $request, OjtLog $ojtLog)
    {
        if ($ojtLog->user_id !== auth()->id()) {
            abort(403);
        }

        if (strtoupper($ojtLog->status) !== 'REJECTED') {
            return redirect()->route('student.logs.index')->with('error', 'You can only edit rejected logs.');
        }

        $validated = $request->validate([
            'am_clock_in' => 'required_with:am_clock_out|nullable|date_format:H:i',
            'am_clock_out' => 'nullable|after:am_clock_in|date_format:H:i',
            'pm_clock_in' => 'required_with:pm_clock_out|nullable|date_format:H:i|after:am_clock_out',
            'pm_clock_out' => 'nullable|after:pm_clock_in|date_format:H:i',
            'activity_summary' => 'required|string|min:20',
            'photo_attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $photoPath = $ojtLog->photo_path;
        if ($request->hasFile('photo_attachment')) {
            $file = $request->file('photo_attachment');
            $fileName = uniqid() . '.jpg';
            $dirPath = storage_path('app/public/ojt_photos');
            
            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0755, true);
            }
            
            $targetPath = $dirPath . '/' . $fileName;
            
            if ($this->compressAndSaveImage($file->getPathname(), $targetPath, 800, 75)) {
                // Delete old photo
                if ($photoPath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($photoPath);
                }
                $photoPath = 'ojt_photos/' . $fileName;
            } else {
                $newPath = $file->store('ojt_photos', 'public');
                if ($photoPath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($photoPath);
                }
                $photoPath = $newPath;
            }
        }

        $totalMinutes = 0;
        
        if ($request->filled('am_clock_in') && $request->filled('am_clock_out')) {
            $amIn = Carbon::createFromFormat('H:i', $request->am_clock_in);
            $amOut = Carbon::createFromFormat('H:i', $request->am_clock_out);
            if ($amOut->lessThan($amIn)) $amOut->addDay();
            $totalMinutes += $amIn->diffInMinutes($amOut);
        }

        if ($request->filled('pm_clock_in') && $request->filled('pm_clock_out')) {
            $pmIn = Carbon::createFromFormat('H:i', $request->pm_clock_in);
            $pmOut = Carbon::createFromFormat('H:i', $request->pm_clock_out);
            if ($pmOut->lessThan($pmIn)) $pmOut->addDay();
            $totalMinutes += $pmIn->diffInMinutes($pmOut);
        }

        $ot_minutes = 0;
        if ($request->filled('ot_clock_in') && $request->filled('ot_clock_out')) {
            $ot_in = Carbon::createFromFormat('H:i', $request->ot_clock_in);
            $ot_out = Carbon::createFromFormat('H:i', $request->ot_clock_out);
            
            if ($ot_out->lessThan($ot_in)) $ot_out->addDay();
            $ot_minutes = $ot_in->diffInMinutes($ot_out);
            $totalMinutes += $ot_minutes;
        }

        // Convert minutes to true decimal hours (e.g. 8 hrs 30 mins = 8.50 hrs)
        $totalHours = round($totalMinutes / 60, 2);
        $otHours = round($ot_minutes / 60, 2);

        $ojtLog->update([
            'morning_in' => $request->am_clock_in,
            'morning_out' => $request->am_clock_out,
            'afternoon_in' => $request->pm_clock_in,
            'afternoon_out' => $request->pm_clock_out,
            'ot_clock_in' => $request->ot_clock_in,
            'ot_clock_out' => $request->ot_clock_out,
            'ot_duration' => $otHours,
            'hours_rendered' => $totalHours,
            'tasks_performed' => $request->activity_summary,
            'photo_path' => $photoPath,
            'status' => 'Pending',
            'remarks' => null, // Clear remarks on resubmit
            'has_overtime' => $request->has('has_overtime')
        ]);

        return redirect()->route('student.logs.index')->with('success', 'Log updated and resubmitted successfully.');
    }

    /**
     * Compress and resize an image before storing it.
     *
     * @param string $sourcePath
     * @param string $destinationPath
     * @param int $maxWidth
     * @param int $quality
     * @return bool
     */
    private function compressAndSaveImage(string $sourcePath, string $destinationPath, int $maxWidth, int $quality): bool
    {
        $imageInfo = @getimagesize($sourcePath);
        if ($imageInfo === false) {
            return false;
        }

        list($width, $height, $type) = $imageInfo;

        switch ($type) {
            case IMAGETYPE_JPEG:
                $srcImage = @imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $srcImage = @imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $srcImage = @imagecreatefromgif($sourcePath);
                break;
            case IMAGETYPE_WEBP:
                $srcImage = @imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }

        if (!$srcImage) {
            return false;
        }

        // Calculate aspect ratio keeping dimensions bounded within $maxWidth
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) round($height * ($maxWidth / $width));
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        $destImage = imagecreatetruecolor($newWidth, $newHeight);
        if (!$destImage) {
            imagedestroy($srcImage);
            return false;
        }

        // Handle transparency by filling the background with white
        $white = imagecolorallocate($destImage, 255, 255, 255);
        imagefill($destImage, 0, 0, $white);

        imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Save as a highly optimized JPEG
        $saved = imagejpeg($destImage, $destinationPath, $quality);

        imagedestroy($srcImage);
        imagedestroy($destImage);

        return $saved;
    }
}
