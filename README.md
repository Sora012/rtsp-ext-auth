## RTSP Simple Server External Auth

### Basic Prerequisites

Requires MySQL/MariaDB, PHP, [RTSP Simple Server](https://github.com/aler9/rtsp-simple-server)

### Basic Setup

1. Setup a webserver  
2. Edit RTSP Simple Server Configuration to contain:  
`rtmpDisable: no`  
`externalAuthenticationURL: https://example.com/rtsp-ext-auth.php`  
3. Adjust externalAuthenticationURL to match your webserver domain  
4. Import MySQL/MariaDB SQL file  
5. Edit the SQL Database to contain information for each accessible stream  
6. Edit "viewers" in the SQL Database with JSON for each viewer allowed to watch the stream.  
7. { "viewer1": "viewer1password", "viewer2": "viewer2password" }

## OBS

**{domain}** is your servers Domain  
**{stream_path}** is the stream_path from the MySQL Database  
**{stream_user}** is the stream_user from the MySQL Database  
**{stream_pass}** is the stream_pass from the MySQL Database  

OBS Stream Settings  
Server: rtmp://{domain}/   
StreamKey: {stream_path}?user={stream_user}&pass={stream_pass}

### Notes
Don't check "Use Authenication"  
**The database should use all lower-case information and keys**
