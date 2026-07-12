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

        // Convert exactly to the human decimal structure: 4 hours 1 min = 4.01
        $totalHrs = floor($totalMinutes / 60);
        $totalMins = $totalMinutes % 60;
        $humanDecimalTotal = (float) sprintf('%d.%02d', $totalHrs, $totalMins);

        $otHrs = floor($ot_minutes / 60);
        $otMins = $ot_minutes % 60;
        $humanDecimalOT = (float) sprintf('%d.%02d', $otHrs, $otMins);

        OjtLog::create([
            'user_id' => auth()->id(),
            'log_date' => $request->log_date,
            'morning_in' => $request->am_clock_in,
            'morning_out' => $request->am_clock_out,
            'afternoon_in' => $request->pm_clock_in,
            'afternoon_out' => $request->pm_clock_out,
            'ot_clock_in' => $request->ot_clock_in,
            'ot_clock_out' => $request->ot_clock_out,
            'ot_duration' => $humanDecimalOT,
            'hours_rendered' => $humanDecimalTotal,
            'tasks_performed' => $request->activity_summary,
            'photo_path' => $photoPath,
            'status' => 'Pending',
            'has_overtime' => $request->has('has_overtime')
        ]);

        return redirect()->back()->with('success', 'OJT Shift Logged Successfully!');
    }

    public function index()
    {
        // Fetch the authenticated student's logs ordered by newest date
        $logs = OjtLog::where('user_id', auth()->id())
            ->orderBy('log_date', 'desc')
            ->paginate(10);

        return view('student.logs', compact('logs'));
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
