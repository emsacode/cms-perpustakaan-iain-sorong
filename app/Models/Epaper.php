<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Epaper extends Model
{
    protected $table = 'epapers';
    protected $guarded = [];

    protected static function booted()
    {
        static::saving(function (Epaper $epaper) {
            // Jika pdf_file dirubah atau baru, dan cover_image kosong, coba ekstrak cover
            if ($epaper->pdf_file && (empty($epaper->cover_image) || $epaper->isDirty('pdf_file'))) {
                try {
                    $pdfDisk = Storage::disk('public');
                    
                    if ($pdfDisk->exists($epaper->pdf_file)) {
                        $pdfPath = $pdfDisk->path($epaper->pdf_file);
                        
                        // Generate nama file cover
                        $pathInfo = pathinfo($epaper->pdf_file);
                        $coverRelativePath = 'epapers/covers/' . $pathInfo['filename'] . '.jpg';
                        
                        // Buat folder cover jika belum ada
                        $pdfDisk->makeDirectory('epapers/covers');
                        
                        // Ekstraksi menggunakan Imagick
                        if (class_exists('\Imagick')) {
                            // [0] untuk mengambil halaman pertama saja
                            $imagick = new \Imagick();
                            
                            // Set resolution sebelum membaca PDF untuk ketajaman kualitas render
                            $imagick->setResolution(150, 150);
                            
                            $imagick->readImage($pdfPath . '[0]');
                            
                            $imagick->setImageFormat('jpeg');
                            $imagick->setImageCompression(\Imagick::COMPRESSION_JPEG);
                            $imagick->setImageCompressionQuality(80); // Kompresi agar di bawah 800 KB
                            
                            // Resize jika terlalu besar
                            $imagick->thumbnailImage(800, 0); // Lebar 800px, tinggi otomatis proportional
                            
                            // Simpan gambar cover
                            $pdfDisk->put($coverRelativePath, $imagick->getImagesBlob());
                            
                            // Set path cover ke database
                            $epaper->cover_image = $coverRelativePath;
                            
                            $imagick->clear();
                            $imagick->destroy();
                        } else {
                            throw new \Exception("Ekstensi PHP Imagick tidak terpasang.");
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("Gagal mengekstrak cover dari PDF '{$epaper->pdf_file}': " . $e->getMessage());
                    
                    // Gunakan default placeholder cover
                    if (empty($epaper->cover_image)) {
                        $epaper->cover_image = 'epapers/covers/default-placeholder.jpg';
                    }
                }
            }
        });
    }
}
