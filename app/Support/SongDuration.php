<?php

namespace App\Support;

class SongDuration
{
    public function getDuration($file)
    {
        try {
            $ext = $file->getClientOriginalExtension();
            if ($ext === 'wav') {
                return $this->wav($file);
            }
            elseif ($ext === 'mp3') {
                return $this->mp3($file);
            }
            return 0;
        }
        catch (\Exception $e) {
            return 0;
        }
    }

    public function mp3($file)
    {
        $mp3 = new MP3File($file->getPathname());
        $seconds = $mp3->getDuration();
        return (floor($seconds/60) % 60) . ':' . str_pad($seconds % 60, 2, '0', STR_PAD_LEFT);
    }

    public function wav($file)
    {
        try {
            $path = $file->getRealPath();

            // Check file exists and has minimum WAV header size
            if (!file_exists($path) || filesize($path) < 44) {
                throw new \Exception('Invalid WAV file (file too small or missing)');
            }

            // Open file and read header
            $handle = fopen($path, 'rb');
            if (!$handle) {
                throw new \Exception('Could not open WAV file');
            }

            try {
                // Verify WAV header
                fseek($handle, 0);
                $riffHeader = fread($handle, 4);
                if ($riffHeader !== 'RIFF') {
                    throw new \Exception('Not a valid WAV file (missing RIFF header)');
                }

                // Read WAV header info
                fseek($handle, 20);
                $format = unpack('v', fread($handle, 2))[1]; // Audio format code

                // Accept various WAV formats including non-standard (0)
                // Common formats: 1=PCM, 3=IEEE float, 6=A-law, 7=μ-law
                // Some WAV files incorrectly use 0 despite being playable
                if (!in_array($format, [0, 1, 3, 6, 7])) {
                    \Log::info('Unsupported WAV format: ' . $format);
                    throw new \Exception('Unsupported WAV format: ' . $format);
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
                $maxIterations = 30; // Avoid infinite loops with malformed files
                $iterations = 0;
                while (!feof($handle) && $iterations < $maxIterations) {
                    $iterations++;
                    $chunkID = fread($handle, 4);
                    if (strlen($chunkID) < 4) {
                        throw new \Exception('Unexpected end of file');
                    }

                    $chunkSizeData = fread($handle, 4);
                    if (strlen($chunkSizeData) < 4) {
                        throw new \Exception('Unexpected end of file');
                    }

                    $chunkSize = unpack('V', $chunkSizeData)[1];

                    if ($chunkID === 'data') {
                        break;
                    }

                    // Skip to the next chunk
                    fseek($handle, $chunkSize, SEEK_CUR);
                }

                if ($chunkID !== 'data') {
                    throw new \Exception('Invalid WAV file structure (data chunk not found)');
                }

                // Calculate duration
                $bytesPerSample = $bitsPerSample / 8;
                $bytesPerSecond = $sampleRate * $channels * $bytesPerSample;

                // Avoid division by zero
                if ($bytesPerSecond <= 0) {
                    throw new \Exception('Invalid WAV parameters');
                }

                $durationSeconds = $chunkSize / $bytesPerSecond;

                // Format as MM:SS
                $minutes = floor($durationSeconds / 60);
                $seconds = floor($durationSeconds % 60);

                return sprintf('%d:%02d', $minutes, $seconds);
            } finally {
                // Ensure handle is closed even if an exception occurs
                if (isset($handle) && is_resource($handle)) {
                    fclose($handle);
                }
            }
        } catch (\Exception $e) {
            // Log the exception
            \Log::warning('WAV duration calculation failed: ' . $e->getMessage());

            // Return a default placeholder duration
            return '0:00';
        }
    }
}
