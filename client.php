<?php
/**
 * Upscale an Image from an URL using API from www.UpscaleAPI.io
 *
 * 1. If image is on the Web > set $imageUrl
 *    If image is on your computer > set $localImagePath
 *
 * 2. Set your $apiKey generated on www.UpscaleAPI.io
 *
 * 3. Execute the script and wait from 5 to 20 seconds
 *
 * It returns URL of the generated image of error message
 *
 */

// --- Configuration ---
// SET IMAGE URL or LOCAL PATH to the image to upscale
$imageUrl = 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Cat_November_2010-1a.jpg/300px-Cat_November_2010-1a.jpg'; 
#$localImagePath = 'C:\Portrait\10711062_n.jpg';

// CHANGE Your API Key > go to www.upscaleapi.io to generate it
$apiKey = 'sk_live_XXX';

// URL of the API
$apiUrl = 'https://api.upscaleapi.io/v1/upscale';

if($localImagePath) {
	if (!file_exists($localImagePath)) {
	    die("Error: File not found at $localImagePath");
	}

	// MIME type (ex: image/jpeg) and binary content
	$imageInfo = getimagesize($localImagePath);
	if (!$imageInfo) {
	    die("Error: The file is not a valid image.");
	}
	$mimeType = $imageInfo['mime']; 

	$binaryData = file_get_contents($localImagePath);
	$base64String = base64_encode($binaryData);

	// Data URI Construction (Format : data:image/xy;base64,....)
	$image = 'data:' . $mimeType . ';base64,' . $base64String; 
}
else {
	$image = $imageUrl;
}

// Data
$data = [
    'image' => $image, // URL of the image to upscale
    'scale' => 4, // Upscaling factor: 2, 4, or 8
    'face_enhance' => false, // Enable face enhancement (+1 credit)
    'format' => 'jpg', // Output format: jpg, png, or webp
    'quality' => 90 // Output quality (1-100)
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // false if you work on localhost without https
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

/**
	Example of JSON Success Response :
	
	{
	"status":"success",
	"data":
		{
			"filename":"300px-Cat_November_2010-1a.jpg",
			"scale":4,
			"face_enhance":false,
			"credits_used":3,
			"remaining_credits":2010,
			"status":"success",
			"thumb_url":"http:\/\/upscaleapi\/storage\/d89ace73-304c-48bf-99ee-724eed956e0c\/20260115160752_a55ae422_thumb.jpg",
			"original_url":"http:\/\/upscaleapi\/storage\/d89ace73-304c-48bf-99ee-724eed956e0c\/20260115160752_a55ae422_orig.jpg",
			"original_width":300,
			"original_height":401,
			"output_url":"http:\/\/upscaleapi\/storage\/d89ace73-304c-48bf-99ee-724eed956e0c\/20260115160752_a55ae422_upscaled.jpg",
			"output_width":1200,
			"output_height":1604,
			"output_format":"jpg",
			"output_quality":90,
			"output_duration":5.866
		}
	}

	Example of JSON Error Response
	
	{
	"status":"error",
	"code":400,
	"message":"Unable to download source image (HTTP 403\/404\/500)."
	}


*/

$result = json_decode($response, true);
if($result['status'] == 'success') print $result['data']['output_url'];
else print 'Error code '.$result['code'].' : '.$result['message'];
?>