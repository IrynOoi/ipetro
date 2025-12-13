const db = require('../config/db');

/**
 * Admin Controller
 * Handles admin-only user management operations
 */

// Middleware to check if user is admin
const requireAdmin = (req, res, next) => {
    if (!req.session.user) {
        return res.status(401).json({
            success: false,
            message: 'Not authenticated'
        });
    }

    if (req.session.user.role !== 'admin') {
        return res.status(403).json({
            success: false,
            message: 'Access denied. Admin privileges required.'
        });
    }

    next();
};

/**
 * Get All Users
 * @route GET /admin/users
 */
exports.getAllUsers = [requireAdmin, async (req, res) => {
    try {
        const result = await db.query(
            'SELECT user_id, username, full_name, role, profile_picture, created_at FROM users ORDER BY created_at DESC'
        );

        res.json({
            success: true,
            data: result.rows
        });

    } catch (error) {
        console.error('Get All Users Error:', error);
        res.status(500).json({
            success: false,
            message: 'Server error while fetching users'
        });
    }
}];

/**
 * Create New User
 * @route POST /admin/users
 */
exports.createUser = [requireAdmin, async (req, res) => {
    try {
        const { username, password, full_name, role } = req.body;

        // Validation
        if (!username || !password) {
            return res.status(400).json({
                success: false,
                message: 'Username and password are required'
            });
        }

        if (role && !['admin', 'user'].includes(role)) {
            return res.status(400).json({
                success: false,
                message: 'Invalid role. Must be admin or user'
            });
        }

        // Check if username already exists
        const existingUser = await db.query(
            'SELECT user_id FROM users WHERE username = $1',
            [username]
        );

        if (existingUser.rows.length > 0) {
            return res.status(400).json({
                success: false,
                message: 'Username already exists'
            });
        }

        // Create user
        const result = await db.query(
            'INSERT INTO users (username, password, full_name, role) VALUES ($1, $2, $3, $4) RETURNING user_id, username, full_name, role, created_at',
            [username, password, full_name || null, role || 'user']
        );

        res.json({
            success: true,
            message: 'User created successfully',
            data: result.rows[0]
        });

    } catch (error) {
        console.error('Create User Error:', error);
        res.status(500).json({
            success: false,
            message: 'Server error while creating user'
        });
    }
}];

/**
 * Update User
 * @route PUT /admin/users/:id
 */
exports.updateUser = [requireAdmin, async (req, res) => {
    try {
        const userId = req.params.id;
        const { username, password, full_name, role } = req.body;

        // Validation
        if (!username) {
            return res.status(400).json({
                success: false,
                message: 'Username is required'
            });
        }

        if (role && !['admin', 'user'].includes(role)) {
            return res.status(400).json({
                success: false,
                message: 'Invalid role. Must be admin or user'
            });
        }

        // Check if username is taken by another user
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

        // Build update query
        let updateQuery;
        let updateParams;

        if (password && password.trim() !== '') {
            updateQuery = 'UPDATE users SET username = $1, password = $2, full_name = $3, role = $4 WHERE user_id = $5 RETURNING user_id, username, full_name, role';
            updateParams = [username, password, full_name || null, role || 'user', userId];
        } else {
            updateQuery = 'UPDATE users SET username = $1, full_name = $2, role = $3 WHERE user_id = $4 RETURNING user_id, username, full_name, role';
            updateParams = [username, full_name || null, role || 'user', userId];
        }

        const result = await db.query(updateQuery, updateParams);

        if (result.rows.length === 0) {
            return res.status(404).json({
                success: false,
                message: 'User not found'
            });
        }

        res.json({
            success: true,
            message: 'User updated successfully',
            data: result.rows[0]
        });

    } catch (error) {
        console.error('Update User Error:', error);
        res.status(500).json({
            success: false,
            message: 'Server error while updating user'
        });
    }
}];

/**
 * Delete User
 * @route DELETE /admin/users/:id
 */
exports.deleteUser = [requireAdmin, async (req, res) => {
    try {
        const userId = req.params.id;

        // Prevent deleting yourself
        if (parseInt(userId) === req.session.user.id) {
            return res.status(400).json({
                success: false,
                message: 'Cannot delete your own account'
            });
        }

        const result = await db.query(
            'DELETE FROM users WHERE user_id = $1 RETURNING username',
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
            message: `User ${result.rows[0].username} deleted successfully`
        });

    } catch (error) {
        console.error('Delete User Error:', error);
        res.status(500).json({
            success: false,
            message: 'Server error while deleting user'
        });
    }
}];
