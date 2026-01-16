# PHP Client for UpscaleAPI.io

This repository contains a ready-to-use PHP client script to interact with the **UpscaleAPI.io** REST API. It allows you to upscale and enhance images using Artificial Intelligence directly from your PHP applications.

The script automatically handles two upload modes:
1.  **Via URL** (Remote image hosted on the web).
2.  **Via Local File** (Image stored on your server/computer, automatically encoded to Base64).

## Features

* **AI Upscaling**: Scale up by 2x, 4x, or 8x.
* **Face Enhancement**: Restore facial details using dedicated AI models.
* **Format Conversion**: Output results in JPG, PNG, or WEBP.
* **Compression Control**: Adjust output quality (1-100).
* **Base64 Support**: Automatically handles local file encoding for secure uploads.

## Prerequisites

* PHP 7.4 or higher.
* `cURL` PHP extension enabled.
* An API Key (Get it from [www.upscaleapi.io](https://www.upscaleapi.io)).

## Configuration

Open the `client.php` file (or whatever you named the script) and modify the **Configuration** section at the top.

### 1. Authentication
Set your private API Key:
```php
$apiKey = 'sk_live_YOUR_API_KEY_HERE';
```

### 2. Image Source Selection

**Option A: Remote Image (URL)**
Leave `$localImagePath` empty or commented out, and set `$imageUrl`.
```php
$imageUrl = '[https://upload.wikimedia.org/.../image.jpg](https://upload.wikimedia.org/.../image.jpg)';
// $localImagePath = '...';
```

**Option B: Local Image (File Path)**
Set the path to your local file. If this variable is set and the file exists, the URL option will be ignored.
```php
// $imageUrl = '...';
$localImagePath = 'C:/Images/my_photo.jpg'; // or '/var/www/uploads/photo.jpg'
```

### 3. API Parameters
You can customize the upscaling process in the `$data` array:

```php
$data = [
    'scale'        => 4,      // Upscaling factor: 2, 4, or 8
    'face_enhance' => false,  // Set to true to enable face restoration (+1 credit)
    'format'       => 'jpg',  // Output format: 'jpg', 'png', or 'webp'
    'quality'      => 90      // Output compression quality (1-100)
];
```

## Usage

Run the script via your terminal or a web browser:

```bash
php client.php
```

### Output
* **Success:** The script will output the direct URL of the generated image (e.g., `https://storage.upscaleapi.io/..._upscaled.jpg`).
* **Error:** The script will output the error code and message (e.g., `Error code 402 : Insufficient credits`).

## JSON Response Structure

The API returns a full JSON object. Here is an example of a successful response for reference:

```json
{
  "status": "success",
  "data": {
    "filename": "image.jpg",
    "scale": 4,
    "face_enhance": false,
    "credits_used": 3,
    "remaining_credits": 2010,
    "status": "success",
    "thumb_url": "[https://storage.upscaleapi.io/.../thumb.jpg](https://storage.upscaleapi.io/.../thumb.jpg)",
    "original_url": "[https://storage.upscaleapi.io/.../orig.jpg](https://storage.upscaleapi.io/.../orig.jpg)",
    "output_url": "[https://storage.upscaleapi.io/.../upscaled.jpg](https://storage.upscaleapi.io/.../upscaled.jpg)",
    "output_width": 1200,
    "output_height": 1604,
    "output_format": "jpg",
    "output_quality": 90,
    "output_duration": 5.866
  }
}
```

## Security (SSL)

The provided script sets `CURLOPT_SSL_VERIFYPEER` to `false` to ensure it works out-of-the-box in local development environments.
**For production environments**, it is highly recommended to set this to `true` to verify SSL certificates:

```php
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
```

## License

This client script is open-source and free to use.