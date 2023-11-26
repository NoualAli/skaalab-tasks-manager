import CryptoJS from "crypto-js";

const secretKey = 'base64:7tiHfMRFCOamQCfiEHt1bWW3C4nnweZin2TDWHKRAAs='

// Encryption function
export function encryptData(data) {
    const ciphertext = CryptoJS.AES.encrypt(data, secretKey);
    return ciphertext.toString();
}

// Decryption function
export function decryptData(ciphertext) {
    const bytes = CryptoJS.AES.decrypt(ciphertext, secretKey);
    const decryptedData = bytes.toString(CryptoJS.enc.Utf8);
    return decryptedData;
}

export function ls_set(key, data) {
    return localStorage.setItem(key, encryptData(data))
}

export function ls_get(key) {
    return decryptData(localStorage.getItem(key))
}
