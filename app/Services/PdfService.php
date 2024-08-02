<?php

namespace App\Services;

use App\Services\AppService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\File;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Storage;

class PdfService extends AppService
{
    protected $disk;
    protected $model;
    protected $storage;

    private $realName;
    private $realExtension;
    private $realSize;
    private $mimeType;

    private $fileDirectory = null;
    private $fileName = null;
    private $filePath = null;

    private $typeName = null;
    private $groupName = null;
    private $thumbnailPath = [];

    /**
     * AppService constructor.
     * @param File $model
     * @param null $disk
     */
    public function __construct(File $model, $disk = null)
    {
        parent::__construct($model);

        $this->disk = !empty($disk) ? $disk : env('UPLOAD_STORAGE', 'public');
    }

    /**
     * Generate a PDF and save it to the storage folder.
     *
     * @param string $viewName
     * @param array $data
     * @param string $fileName
     * @return string The path to the saved PDF
     */
    public function generateAndSavePdf(string $viewName, $newClass, $data, string $fileName): string
    {
        // Generate the PDF from a view
        $pdf = Pdf::loadView($viewName, []);

        // Path to save the PDF
        $filePath = 'public/uploads/' . date('Y') . '/' . date('m') . '/' . $fileName . '.pdf';

        // Save the PDF to the storage folder
        Storage::put($filePath, $pdf->output());

        \DB::beginTransaction();

        try {
            $fileRecord = $this->model->newQuery()->create([
                'group'      => 'document',
                'visibility' => 'private',
                'real_name'  => $fileName . '.pdf',
                'extension'  => 'pdf',
                'size'       => 25924,
                'mime_type'  => 'application/pdf',
                'file_dir'   => 'public/uploads/' . date('Y') . '/' . date('m'),
                'file_name'  => $fileName . '.pdf',
                'file_path'  => 'uploads/' . date('Y') . '/' . date('m') . '/' . $fileName . '.pdf',
                'fileable_type' => $newClass,
                'fileable_id'   => $data['id'],
            ]);
            //code...
            \DB::commit(); // commit the changes
            return $filePath;
        } catch (\Exception $exception) {
            //throw $th;
            \DB::rollBack(); // rollback the changes
            return $exception->getMessage();
        }

        // Return the path to the saved PDF
    }


    public function handleFile(UploadedFile $file, $storageDisk = null)
    {
        $this->setStorageDisk($storageDisk);
        $this->processFile($file);

        return $this;
    }

    public function deleteFiles($filePath): bool
    {
        // if $filePath is in array form
        if (is_array($filePath) && !empty($filePath)) {
            $files = array_map(function ($path) {
                if ($this->storage()->has($path)) {
                    return $path;
                }

                return null;
            }, $filePath);

            return $this->storage()->delete($files);
        }

        // if $filePath is a string
        if (is_string($filePath) && $this->storage()->has($filePath)) {
            return $this->storage()->delete($filePath);
        }

        return false;
    }

    public function saveToDb($group = null)
    {
        DB::beginTransaction();

        try {
            // get storage visibility
            $getVisibility = $this->storage()->getVisibility($this->filePath);

            $fileRecord = $this->model->newQuery()->create([
                'group'      => $group,
                'visibility' => $getVisibility,
                'real_name'  => $this->realName,
                'extension'  => $this->realExtension,
                'size'       => $this->realSize,
                'mime_type'  => $this->mimeType,
                'file_dir'   => $this->normalizeBackslash($this->fileDirectory),
                'file_name'  => $this->fileName,
                'file_path'  => $this->normalizeBackslash($this->filePath),
            ]);

            // $fileRecord['storage_prefix'] = "{$storagePrefix[0]}/{$storagePrefix[1]}";
            //            $fileRecord['base_url'] = $this->storage()->url(null);
            // $fileRecord['file_real_path'] = $this->storage()->path($this->filePath);

            DB::commit();
            return $fileRecord;
        } catch (\Exception $exception) {
            DB::rollBack();

            return $exception->getMessage();
        }
    }

    public function toArray()
    {
        $getVisibility = $this->storage()->getVisibility($this->filePath);

        return [
            'visibility' => $getVisibility,
            'real_name'  => $this->realName,
            'extension'  => $this->realExtension,
            'size'       => $this->realSize,
            'mime_type'  => $this->mimeType,
            'file_dir'   => $this->normalizeBackslash($this->fileDirectory),
            'file_name'  => $this->fileName,
            'file_path'  => $this->normalizeBackslash($this->filePath),
            'thumbnails' => $this->thumbnailPath,
            'base_url'   => $this->storage()->url(null),
        ];
    }

    protected function storage()
    {
        return Storage::disk($this->disk);
    }

    protected function setStorageDisk($disk = null)
    {
        if (isset($disk)) {
            $this->disk = $disk;
        }
    }

    protected function processFile($data)
    {
        $this->realName         = '';
        $this->realExtension    = 'pdf';
        $this->realSize         = 0;
        $this->mimeType         = 'application/pdf';

        $this->fileDirectory  = $this->uploadDirectory('uploads');
        $this->fileName       = $this->generateNewName()  . ".{$this->realExtension}";
        $this->filePath       = $this->fileDirectory . $this->fileName;
    }

    protected function generateNewName(string $prefix = null, string $suffix = null)
    {
        $prefix_ = (!empty($prefix)) ? trim("{$prefix}_") : null;
        $_suffix = (!empty($prefix)) ? trim("_{$suffix}") : null;

        return $prefix_ . Str::uuid()->toString() . $_suffix;
    }

    protected function uploadDirectory(string $dirPath = null)
    {
        return trim($dirPath, '/ ') . "/" . date('Y') . "/" . date('m');
    }

    /**
     * @param string $subject
     * @return string|string[]
     */
    private function normalizeBackslash(string $subject)
    {
        return str_replace('\\', '/', $subject);
    }
}
