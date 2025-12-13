const db = require('../config/db');

/**
 * User Profile Controller
 * Handles user profile viewing and editing
 */

/**
 * Get Current User Profile
 * @route GET /user/profile
 */
exports.getProfile = async (req, res) => {
    try {
        if (!req.session.user) {
            return res.status(401).json({
                success: false,
                message: 'Not authenticated'
            });
        }

        const userId = req.session.user.id;

        const result = await db.query(
            'SELECT user_id, username, full_name, role, profile_picture, created_at FROM users WHERE user_id = $1',
            [userId]
        );

        if (result.rows.length === 0) {
            return res.status(404).json({
                success: false,
                message: 'User not found'
            });
        }

        res.json({
            success: true,
            data: result.rows[0]
        });

    } catch (error) {
        console.error('Get Profile Error:', error);
        res.status(500).json({
            success: false,
            message: 'Server error while fetching profile'
        });
    }
};

/**
 * Update User Profile
 * @route PUT /user/profile
 */
exports.updateProfile = async (req, res) => {
    try {
        if (!req.session.user) {
            return res.status(401).json({
                success: false,
                message: 'Not authenticated'
            });
        }

        const userId = req.session.user.id;
        const { username, password, full_name } = req.body;

        // Validation
        if (!username || username.trim() === '') {
            return res.status(400).json({
                success: false,
                message: 'Username is required'
            });
        }

        // Check if username is already taken by another user
        const usernameCheck = await db.query(
            'SELECT user_id FROM users WHERE username = $1 AND user_id != $2',
            [username, userId]
        );

        if (usernameCheck.rows.length > 0) {
            return res.status(400).json({
                success: false,
                message: 'Username already taken'
            });
        }

        // Get profile picture from file upload if exists
        const profilePicture = req.file ? `/uploads/profiles/${req.file.filename}` : null;

        // Build update query dynamically based on provided fields
        let updateQuery;
        let updateParams;

        if (password && password.trim() !== '') {
            // Update username, password, full_name, and optionally profile picture
            if (profilePicture) {
                updateQuery = 'UPDATE users SET username = $1, password = $2, full_name = $3, profile_picture = $4 WHERE user_id = $5 RETURNING user_id, username, full_name, role, profile_picture';
                updateParams = [username, password, full_name || null, profilePicture, userId];
            } else {
                updateQuery = 'UPDATE users SET username = $1, password = $2, full_name = $3 WHERE user_id = $4 RETURNING user_id, username, full_name, role, profile_picture';
                updateParams = [username, password, full_name || null, userId];
            }
        } else {
            // Update username, full_name, and optionally profile picture
            if (profilePicture) {
                updateQuery = 'UPDATE users SET username = $1, full_name = $2, profile_picture = $3 WHERE user_id = $4 RETURNING user_id, username, full_name, role, profile_picture';
                updateParams = [username, full_name || null, profilePicture, userId];
            } else {
                updateQuery = 'UPDATE users SET username = $1, full_name = $2 WHERE user_id = $3 RETURNING user_id, username, full_name, role, profile_picture';
                updateParams = [username, full_name || null, userId];
            }
        }

        const result = await db.query(updateQuery, updateParams);

        if (result.rows.length === 0) {
            return res.status(404).json({
                success: false,
                message: 'User not found'
            });
        }

        // Update session with new username
        req.session.user.username = result.rows[0].username;

        res.json({
            success: true,
            message: 'Profile updated successfully',
            data: result.rows[0]
        });

    } catch (error) {
        console.error('Update Profile Error:', error);
        res.status(500).json({
            success: false,
            message: 'Server error while updating profile'
        });
    }
};
