const JWT = require('jsonwebtoken');
const User = require('../models/userModel'); // Replace with your user model

module.exports = async (req, res, next) => {
    try {
        const token = req.headers['authorization'].split(" ")[1];
        JWT.verify(token, process.env.JWT_SECRET, async (err, decoded) => {
            if (err) {
                return res.status(401).send({
                    success: false,
                    message: 'Auth Failed'
                });
            } else {
                req.body.userId = decoded.userId;

                try {
                    const user = await User.findById(decoded.userId);
                    if (!user) {
                        return res.status(404).send({
                            success: false,
                            message: 'User not found'
                        });
                    }

                    next();
                } catch (error) {
                    console.error(error);
                    return res.status(500).send({
                        success: false,
                        error,
                        message: 'Internal Server Error'
                    });
                }
            }
        });
    } catch (error) {
        console.error(error);
        return res.status(401).send({
            success: false,
            error,
            message: 'Auth Failed'
        });
    }
};
