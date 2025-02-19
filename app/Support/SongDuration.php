<?php

namespace App\Support;

class SongDuration
{
    public function getDuration($file)
    {
        $ext = $file->getClientOriginalExtension();
        if ($ext === 'wav') {
            return $this->wav($file);
        }
        elseif ($ext === 'mp3') {
            return $this->mp3($file);
        }
        return 0;
    }

    public function mp3($file)
    {
        $mp3 = new MP3File($file->getPathname());
        $seconds = $mp3->getDuration();
        return (floor($seconds/60) % 60) . ':' . str_pad($seconds % 60, 2, '0', STR_PAD_LEFT);
    }

    public function wav($file)
    {
        $path = $file->getRealPath();

        // Open file and read header
        $handle = fopen($path, 'rb');
        if (!$handle) {
            throw new \Exception('Could not open WAV file');
        }

        // Read WAV header info
        fseek($handle, 20);
        $format = unpack('v', fread($handle, 2))[1]; // Audio format code

        if ($format != 1) { // 1 is PCM (standard)
            fclose($handle);
            throw new \Exception('Unsupported WAV format');
        }

        fseek($handle, 22);
        $channels = unpack('v', fread($handle, 2))[1]; // Number of channels

        fseek($handle, 24);
        $sampleRate = unpack('V', fread($handle, 4))[1]; // Sample rate

        fseek($handle, 34);
        $bitsPerSample = unpack('v', fread($handle, 2))[1]; // Bits per sample

        // Find data chunk
        $chunkID = '';
        $chunkSize = 0;
        fseek($handle, 36);

        // Look for the 'data' chunk
        while (!feof($handle)) {
            $chunkID = fread($handle, 4);
            $chunkSize = unpack('V', fread($handle, 4))[1];

            if ($chunkID === 'data') {
                break;
            }

            // Skip to the next chunk
            fseek($handle, $chunkSize, SEEK_CUR);
        }

        fclose($handle);

        if ($chunkID !== 'data') {
            throw new \Exception('Invalid WAV file structure');
        }

        // Calculate duration
        $bytesPerSample = $bitsPerSample / 8;
        $bytesPerSecond = $sampleRate * $channels * $bytesPerSample;
        $durationSeconds = $chunkSize / $bytesPerSecond;

        // Format as MM:SS
        $minutes = floor($durationSeconds / 60);
        $seconds = floor($durationSeconds % 60);

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
