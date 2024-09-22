<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOTC: Story Teller</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <style>
        /* Basic styling for the form */
        body {
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .container {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: calc(100% - 100px);
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: inline-block;
        }

        .form-group button {
            padding: 8px 16px;
            font-size: 14px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
            vertical-align: top;
            /* margin-left: 10px; */
            margin-top: 4px;
        }

        .form-group .danger button {
            padding: 8px 16px;
            font-size: 14px;
            color: #fff;
            background-color: #FF1D00;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
            vertical-align: top;
            /* margin-left: 10px; */
            margin-top: 4px;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .form-group .danger button:hover {
            background-color: #711D00;
        }

        .message {
            margin-top: 20px;
            color: green;
            opacity: 1;
            transition: opacity 2s ease;
        }

        .fade-out {
            opacity: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Settings</h2>
        <div class="form-group">
            <label for="time">Time (seconds):</label>
            <input type="number" id="time" name="time">
            <button onclick="saveField('time')">Save Time</button>
        </div>
        <div class="form-group">
            <label for="timer_running">Timer Running:</label>
            <input type="checkbox" id="timer_running" name="timer_running" onchange="saveField('timer_running')">
            <label for="timer_running">Reset Timer:</label>
            <input type="checkbox" id="reset_timer" name="reset_timer" onchange="saveField('reset_timer')">
            <button onclick="saveField('timer_running'); saveField('reset_timer')">Save Timer Status</button>
        </div>
        <div class="form-group">
            <label for="day">Day:</label>
            <input type="text" id="day" name="day">
            <button onclick="saveField('day')">Save Day</button>
        </div>
        <div class="form-group">
            <label for="background_image">Background Image URL:</label>
            <input type="text" id="background_image" name="background_image">
            <button onclick="saveField('background_image')">Save Background Image</button>
        </div>
        <div class="message" id="message"></div>
        <div class="form-group">
            <span class="danger">
                <button onclick="flush();getFields();">Flush Memory</button>
            </span>
        </div>
        <div class="message" id="message2"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            window.getFields = function() {
                // Fetch existing settings and populate the form
                fetch('get_settings.php')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('time').value = data.time;
                        document.getElementById('timer_running').checked = data.timer_running;
                        document.getElementById('reset_timer').checked = data.reset_timer;
                        document.getElementById('day').value = data.day;
                        document.getElementById('background_image').value = data.background_image;
                    });
            };
            getFields();

            // Function to save individual fields
            window.saveField = function(fieldName) {
                const formData = new FormData();
                formData.append('field', fieldName);
                formData.append('value', document.getElementById(fieldName).value);
                
                if (fieldName === 'timer_running') {
                    formData.append('value', document.getElementById(fieldName).checked);
                }
                if (fieldName === 'reset_timer') {
                    formData.append('value', document.getElementById(fieldName).checked);
                }

                fetch('save_settings.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    const messageDiv = document.getElementById('message');

                    // Reset opacity and remove fade-out class
                    messageDiv.classList.remove('fade-out');
                    // messageDiv.style.opacity = '1'; // Ensure it's fully visible

                    messageDiv.textContent = result;

                    // Trigger fade-out after 3 seconds
                    setTimeout(() => {
                        messageDiv.classList.add('fade-out');
                    }, 3000);
                })
                .catch(error => {
                    const messageDiv = document.getElementById('message');
                    messageDiv.textContent = 'Error saving settings';
                    console.error('Error:', error);
                });
            };

            // Function to save individual fields
            window.flush = function() {

                fetch('flush.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message);
                    const messageDiv = document.getElementById('message2');

                    // Reset opacity and remove fade-out class
                    messageDiv.classList.remove('fade-out');
                    // messageDiv.style.opacity = '1'; // Ensure it's fully visible

                    messageDiv.textContent = data.message;

                    // Trigger fade-out after 3 seconds
                    setTimeout(() => {
                        messageDiv.classList.add('fade-out');
                    }, 3000);
                })
                .catch(error => {
                    const messageDiv = document.getElementById('message2');
                    messageDiv.textContent = 'Error saving settings';
                    console.error('Error:', error.message);
                });

            };
        });
    </script>
</body>
</html>
