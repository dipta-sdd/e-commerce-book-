const userModel = require("../models/userModel");
const nodemailer = require("nodemailer");
const otpGenerator = require("otp-generator");

// Function to generate OTP
const generateOTP = () => {
    return otpGenerator.generate(6, { digits: true, alphabets: false, upperCase: false, specialChars: false });
};

// Function to send OTP to user's email
const sendOTPToUser = async (email, otp) => {
    try {
        // Create a nodemailer transporter with your email service credentials
        const transporter = nodemailer.createTransport({
            service: 'gmail',
            host: 'smtp.gmail.com',
            port: 587,
            secure: false,
            requireTLS: true,
            auth: {
                user: 'botbookhaven@gmail.com',
                pass: 'ktwv tamj mppy krvj'
            }
        });

        await transporter.sendMail({
            from: 'botbookhaven@gmail.com',
            to: email,
            subject: 'OTP Verification',
            text: `Your OTP for verification is: ${otp}`
        });

        console.log('OTP sent to user successfully.');
    } catch (error) {
        console.error('Error sending OTP:', error);
    }
};

// Function to save OTP and its expiry time in the user's record in the database
const saveOTPToUser = async (userId, otp) => {
    try {
        const user = await userModel.findById(userId);

        if (!user) {
            console.error('User not found.');
            return;
        }

        // Save OTP and its expiry time in the user's record
        const expiry = new Date(Date.now() + 5 * 60000); // OTP expiry time (e.g., 5 minutes)
        user.otp.code = otp;
        user.otp.expiry = expiry;
        await user.save();

        console.log('OTP saved to user successfully.');
    } catch (error) {
        console.error('Error saving OTP to user:', error);
    }
};

// Function to generate OTP, send it to user's email, and save it in the database
const generateAndSendOTP = async (userId, userEmail) => {
    try {
        const otp = generateOTP(); // Generate OTP
        await sendOTPToUser(userEmail, otp); // Send OTP to user's email
        await saveOTPToUser(userId, otp); // Save OTP to user's record in the database
    } catch (error) {
        console.error('Error generating and sending OTP:', error);
    }
};

const verifyOTP = async (userId, submittedOTP) => {
    try {
        const user = await userModel.findById(userId);

        if (!user || !user.otp || !user.otp.code || !user.otp.expiry) {
            return false;
        }

        if (user.otp.code === submittedOTP && user.otp.expiry > new Date()) {
            // OTP matches and has not expired
            return true;
        }
        console.log(user.otp.expiry)
        console.log(new Date())
        return false;
    } catch (error) {
        console.error('Error verifying OTP:', error);
        return { success: false, message: 'Internal server error' };
    }
};

module.exports = { generateAndSendOTP, verifyOTP };
