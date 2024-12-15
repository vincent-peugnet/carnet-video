Capturing video clip
====================

Approach to catpture clip in lossless format using graphical preview and frame precision.

## encode video

I use MPV with an encoding Lua script stored in this repo:

<https://github.com/occivink/mpv-scripts> / `encode.lua`

Which use MPV to define boudaries, and send them to `ffmpeg`.


### encode.lua

To use the script, install the file here:

    ~/.config/mpv/scripts/encode.lua


### custom configuration

In order to customize encoding and output settings, a configuration file should be created here:

    ~/.config/mpv/script-opts/<encode_custom_conf>.conf

Here is the config best suited to work with this program.

    # format of the output filename
    # Use an incremental number
    output_format=$n.mkv

    # where to put the file
    # should be pointed to "src/clips"
    output_directory=<PATH/TO/SRC/CLIPS>


    # Only copy one video and one audio stream
    # Video is lossy X264 with CRF 18,to obtain a decent quality
    # Audio is lossy good quality OPUS 
    codec=-map -0 -map 0:v:0 -map 0:a:0? -ac 1 -map_metadata -1 -c:v libx264 -preset slow -crf 18 -b:a 160k


### input.conf

Key binding to trigger the script have to be defined in this main config:

    ~/.config/mpv/input.conf

Encoding script and custom configuration have to be specified like this (withouf file extension):

    e script-message-to encode set-timestamp <encode_custom_conf>

Here, the key `e` is defined to lauch the script in point. 


## Frame by frame naviguation

Custom shortcut can be scpecified in:

    ~/.config/mpv/input.conf

By default, `,` and `.` are used go back and forth.
I've just replaced the dot with `;`, as it's more practical on an AZERTY keyboard.

    # advance one frame and pause
    ; frame-step

And when pressing `Alt` key, it move ~1s back and forth (not precise but more efficient as it use only keeframes)

    # seek ~1 second before or after, using keyframes
    Alt+, seek -1
    Alt+; seek 1


## Pasting URL

MPV doest not support pasting URL like VLC.
This can be achieved using this script:

<https://github.com/Eisa01/mpv-scripts> / `SmartCopyPaste.lua`

Put this file in the script folder:

    ~/.config/mpv/scripts/SmartCopyPaste.lua
