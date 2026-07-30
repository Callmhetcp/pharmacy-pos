<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    /**
     * Show Backup Page
     */
    public function index()
    {
        $files = collect(Storage::disk('local')->files('Pharmacy Inventory'))
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => number_format(
                        Storage::disk('local')->size($file) / 1024 / 1024,
                        2
                    ) . ' MB',
                    'date' => date(
                        'd M Y H:i',
                        Storage::disk('local')->lastModified($file)
                    ),
                ];
            });

        return view('settings.backup', compact('files'));
    }

    /**
     * Create Backup
     */
  public function create()
{
    $backupFolder = storage_path('app/private/Pharmacy Inventory');

    if (!File::exists($backupFolder)) {
        File::makeDirectory($backupFolder, 0755, true);
    }

    $fileName = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';

    $filePath = $backupFolder . DIRECTORY_SEPARATOR . $fileName;

    $mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';

    $host = env('DB_HOST');
    $port = env('DB_PORT');
    $database = env('DB_DATABASE');
    $username = env('DB_USERNAME');
    $password = env('DB_PASSWORD');

    $command = "\"{$mysqldump}\" "
        . "--host={$host} "
        . "--port={$port} "
        . "--user={$username} ";

    if (!empty($password)) {
        $command .= "--password={$password} ";
    }

    $command .= "{$database} > \"{$filePath}\"";

    exec($command . " 2>&1", $output, $result);

    if ($result !== 0) {
        return back()->with(
            'error',
            implode("<br>", $output)
        );
    }

    return back()->with(
        'success',
        'Database backup created successfully.'
    );
}
    /**
     * Download Backup
     */
    public function download($file)
    {
        $path = storage_path(
            'app/private/Pharmacy Inventory/' . $file
        );

        if (!file_exists($path)) {

            return back()->with(
                'error',
                'Backup file not found.'
            );

        }

        return response()->download($path);
    }

    /**
     * Restore Backup
     */
public function restore(Request $request)
{
    $request->validate([
        'backup_file' => 'required',
    ]);

    $sqlFile = storage_path(
        'app/private/Pharmacy Inventory/' . $request->backup_file
    );

    if (!file_exists($sqlFile)) {

        return back()->with(
            'error',
            'Backup file not found.'
        );

    }

    $mysql = 'C:\\xampp\\mysql\\bin\\mysql.exe';

    $host = env('DB_HOST');
    $port = env('DB_PORT');
    $database = env('DB_DATABASE');
    $username = env('DB_USERNAME');
    $password = env('DB_PASSWORD');

    $command = "\"{$mysql}\" "
        . "--host={$host} "
        . "--port={$port} "
        . "--user={$username} ";

    if (!empty($password)) {
        $command .= "--password={$password} ";
    }

    $command .= "{$database} < \"{$sqlFile}\"";

    exec("cmd /c {$command} 2>&1", $output, $result);

    if ($result !== 0) {

        return back()->with(
            'error',
            implode('<br>', $output)
        );

    }

    return redirect()
        ->route('backup.index')
        ->with(
            'success',
            'Database restored successfully.'
        );
}
    public function destroy($file)
{
    $path = 'Pharmacy Inventory/' . $file;

    if (!Storage::disk('local')->exists($path)) {

        return back()->with(
            'error',
            'Backup file not found.'
        );

    }

    Storage::disk('local')->delete($path);

    return back()->with(
        'success',
        'Backup deleted successfully.'
    );
}
}