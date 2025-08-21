<?php

namespace App\Controllers;

use App\Http\Exceptions\BadRequestException;
use App\Http\Exceptions\MethodNotAllowedException;
use App\Services\CsvImportService;
use finfo;

class CsvController extends Controller
{
    private array $allowedMimes = ['text/csv', 'text/plain', 'application/vnd.ms-excel'];

    private array $uploadErrors = [
        UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive.',
        UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive.',
        UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
    ];

    public function __construct(private readonly CsvImportService $csvImportService = new CsvImportService())
    {

    }

    /**
     * @throws BadRequestException
     * @throws MethodNotAllowedException
     */
    public function upload(): array
    {
        $this->verifyRequestMethod('POST');
        $this->verifyFileUpload();

        $file = $_FILES['file'];

        $this->checkUploadErrors($file);

        $tmpPath = $file['tmp_name'] ?? '';

        $this->checkTmpFileWritten($tmpPath);

        $this->validateFileType($tmpPath, $file);

        $this->csvImportService->importFromFile($tmpPath);

        return [
            'message' => 'Import completed'
        ];
    }

    /**
     * @throws BadRequestException
     */
    private function verifyFileUpload(): void
    {
        if (!isset($_FILES['file']) || !is_array($_FILES['file'])) {
            throw new BadRequestException('No file upload in request');
        }
    }

    /**
     * @throws BadRequestException
     */
    private function checkUploadErrors(array $file): void
    {
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new BadRequestException($this->uploadErrors[(int)$file['error']] ?? 'Upload failed.');
        }
    }

    /**
     * @throws BadRequestException
     */
    private function checkTmpFileWritten(string $tmpPath): void
    {
        if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
            throw new BadRequestException('Invalid file upload');
        }

        if (!is_readable($tmpPath)) {
            throw new BadRequestException('File is not readable');
        }
    }

    /**
     * @throws BadRequestException
     */
    private function validateFileType(string $tmpPath, array $file): void
    {
        $originalName = (string)($file['name'] ?? '');
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            throw new BadRequestException('Only CSV files are allowed');
        }

        $fileInfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $fileInfo->file($tmpPath) ?: '';

        if (!in_array($mime, $this->allowedMimes, true)) {
            throw new BadRequestException('Invalid MIME type');
        }
    }
}