<?php

function ReqResGPT($api_key, $input_text)
{
    // Set the OpenAI endpoint for chat completions
    $endpoint = 'https://api.openai.com/v1/chat/completions';

    // Set the headers for the API request
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
    ];

    // Set the data for the API request
    $data = [
        'model' => 'gpt-3.5-turbo-1106',  // Specify the chat model
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
            ['role' => 'user', 'content' => "Analyze the following text and provide a report number and the sample description of the item , and could you tell me the analytical (tests) Names and their ratings (all of them in the text) ,and the analytical summary from the text and i need the response to be like this: Report Number: (the number)\n\nItem Description: \n (item description)\n\nAnalytical Summary:\n (the summary)\n\nNames and Ratings:\n(the name) - (the rating) \n\n " . $input_text],
        ],
        'max_tokens' => 1000,  // You can adjust this based on the desired length of the completion
    ];

    // Initialize cURL session
    $ch = curl_init($endpoint);

    // Set cURL options
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Execute cURL session and get the response
    $response = curl_exec($ch);
    print_r($response);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        // Handle the error as needed
    }

    // Close cURL session
    curl_close($ch);

    // Decode the JSON response
    $response_data = json_decode($response, true);

    // Check if 'choices' key exists and is not null
    if (isset($response_data['choices']) && !empty($response_data['choices'])) {
        $choices = $response_data['choices'];
        $generated_text = $choices[0]['message']['content'];
        // Extracted content from the user message
        return $generated_text;
    } else {
        echo "No choices in the response.\n";
    }
}
