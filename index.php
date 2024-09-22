<?php 
    $server = "http://localhost:8080/www/botc/";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>BOTC</title>
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <script>
            let timerRunning = false;
            let remainingTime = 300;
            let debug = <?php echo 'false' ?>;
            
            
            function formatTimeLeft(totalSeconds) 
            {
                if (totalSeconds <= 0) {
                    return "Time's up";
                }

                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;

                // Determine the appropriate format based on the time left
                if (minutes > 0) {
                    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} Minutes left`;
                } else {
                    return `${seconds.toString().padStart(2, '0')} Seconds left`;
                }
            }

            function check() {

                const url = "<?php echo $server . 'get_settings.php' ?>";

                const xhr = new XMLHttpRequest();

                xhr.open('GET', url, true);
                xhr.onload = function() 
                {
                    if (xhr.status >= 200 && xhr.status < 300) 
                    {
                        const data = JSON.parse(xhr.responseText);

                        if (debug)
                        {
                            console.log('Time:', data.time);
                            console.log('Timer Running:', data.timer_running);
                            console.log('Reset Timer:', data.reset_timer);
                            console.log('Day:', data.day);
                            console.log('Background Image URL:', data.background_image);
                        }
                        

                        document.getElementById('day').innerText = data.day;

                        timerRunning = data.timer_running;
                        if (timerRunning != true)
                        {
                            if (data.reset_timer)
                            {
                                if (debug)
                                {
                                    console.log("RESET TIMER");
                                }
                                document.getElementById('timer').innerText = formatTimeLeft(data.time);
                                remainingTime = data.time;
                            }
                            // we're paused, waiting for the next update
                        }
                        else
                        {
                            remainingTime = remainingTime - 1;
                            if (debug)
                            {
                                console.log(formatTimeLeft(remainingTime));
                            }
                            document.getElementById('timer').innerText = formatTimeLeft(remainingTime);
                        }
                    } 
                    else 
                    {
                        console.error('Request failed with status:', xhr.status);
                    
                    }; 
                }

                xhr.onerror = function() 
                {
                    console.error('Network error occurred');
                };

                xhr.send();

            }
            

            document.addEventListener('DOMContentLoaded', () => {
                check(); // Fetch immediately
                pollingInterval = setInterval(check, 1000); // Fetch every second

            });
        </script>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>

        <!-- Video Background -->
        <video class="video-background" autoplay muted loop>
            <source src="media/clock.mp4" type="video/mp4"> <!-- Replace with your video URL -->
            Your browser does not support the video tag.
        </video>
        
        <div class="container">
            <div id="day">
                Day 1
            </div>
            <br>
            <div id="timer">
                05:00 Minutes
            </div>
        </div>
        <!-- Thanks to Chuck Davis for the font!
            -> https://letterheadfonts.com/fonts/unlovable.php
        -->
    </body>
</html>