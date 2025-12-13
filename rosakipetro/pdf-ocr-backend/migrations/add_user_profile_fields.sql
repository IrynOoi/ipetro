-- ========================================
-- iPETRO RBIMS - Database Migration Script
-- Add Profile Picture and Full Name Support
-- ========================================

-- Add new columns to users table
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS full_name VARCHAR(100),
ADD COLUMN IF NOT EXISTS profile_picture VARCHAR(500);

-- Add comments for documentation
COMMENT ON COLUMN users.full_name IS 'User full/display name';
COMMENT ON COLUMN users.profile_picture IS 'Path or URL to user profile picture';

-- Optional: Set default values for existing users
-- UPDATE users SET full_name = username WHERE full_name IS NULL;

-- Verify the changes
SELECT column_name, data_type, character_maximum_length 
FROM information_schema.columns 
WHERE table_name = 'users' 
AND column_name IN ('full_name', 'profile_picture');

-- Display result
SELECT 'Migration completed successfully. New columns added to users table.' AS status;
