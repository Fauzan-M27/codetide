#!/usr/bin/env python3
"""
Convert sprite sheets to animated GIFs
Requires: pip install Pillow
"""

import os
from PIL import Image

# Configuration
ASSET_DIR = r"d:\Multimedia Pembelajaran\CodeTide\Asset Karakter"
OUTPUT_DIR = r"d:\Multimedia Pembelajaran\CodeTide\laravel-app\public\characters"

# Character folders to process
CHARACTERS = [
    "City_men_1", "City_men_2", "City_men_3",
    "Cowboy_1", "Cowboy_2", "Cowboy_3",
    "Fighter", "Gangsters_1", "Gangsters_2", "Gangsters_3",
    "Girl_1", "Girl_2", "Girl_3",
    "Samurai", "Shinobi",
    "Wild Zombie", "Zombie Man", "Zombie Woman"
]

# Sprite configurations (sprite_name: (frame_width, frame_height, num_frames, duration_ms))
SPRITE_CONFIGS = {
    "Idle.png": (128, 128, 6, 100),      # 6 frames, 100ms each
    "Walk.png": (128, 128, 8, 100),      # 8 frames
    "Run.png": (128, 128, 8, 80),        # 8 frames, faster
    "Attack.png": (128, 128, 6, 100),    # 6 frames
    "Attack_1.png": (128, 128, 4, 100),  # 4 frames
    "Attack_2.png": (128, 128, 4, 100),
    "Attack_3.png": (128, 128, 4, 100),
    "Hurt.png": (128, 128, 3, 150),      # 3 frames, slower
    "Jump.png": (128, 128, 5, 100),      # 5 frames
    "Shot.png": (128, 128, 4, 100),      # 4 frames
}

def convert_sprite_sheet_to_gif(sprite_path, output_path, frame_width, frame_height, num_frames, duration):
    """Convert horizontal sprite sheet to animated GIF"""
    try:
        # Load sprite sheet
        sprite_sheet = Image.open(sprite_path).convert("RGBA")
        
        # Extract frames
        frames = []
        for i in range(num_frames):
            # Crop each frame
            left = i * frame_width
            frame = sprite_sheet.crop((left, 0, left + frame_width, frame_height))
            frames.append(frame)
        
        # Save as animated GIF
        frames[0].save(
            output_path,
            save_all=True,
            append_images=frames[1:],
            duration=duration,
            loop=0,  # Loop forever
            transparency=0,
            disposal=2,
            optimize=False
        )
        
        print(f"✓ Created: {output_path}")
        return True
        
    except Exception as e:
        print(f"✗ Error processing {sprite_path}: {e}")
        return False

def main():
    converted_count = 0
    total_count = 0
    
    for character in CHARACTERS:
        char_input_dir = os.path.join(ASSET_DIR, character)
        char_output_dir = os.path.join(OUTPUT_DIR, character)
        
        # Create output directory if not exists
        os.makedirs(char_output_dir, exist_ok=True)
        
        print(f"\n📦 Processing {character}...")
        
        for sprite_name, (frame_width, frame_height, num_frames, duration) in SPRITE_CONFIGS.items():
            sprite_path = os.path.join(char_input_dir, sprite_name)
            
            if os.path.exists(sprite_path):
                total_count += 1
                output_name = sprite_name.replace(".png", ".gif")
                output_path = os.path.join(char_output_dir, output_name)
                
                if convert_sprite_sheet_to_gif(sprite_path, output_path, frame_width, frame_height, num_frames, duration):
                    converted_count += 1
    
    print(f"\n{'='*60}")
    print(f"✨ Conversion complete!")
    print(f"📊 Successfully converted: {converted_count}/{total_count} sprites")
    print(f"{'='*60}")

if __name__ == "__main__":
    main()
