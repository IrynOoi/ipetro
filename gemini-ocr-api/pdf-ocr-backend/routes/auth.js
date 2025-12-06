const express = require('express');
const router = express.Router();
const authController = require('../controllers/Authcontroller');

/**
 * Authentication Routes
 * All routes prefixed with /auth
 */

// Login
router.post('/login', authController.login);

// Logout
router.post('/logout', authController.logout);

// Check Authentication Status
router.get('/me', authController.checkAuth);

module.exports = router;