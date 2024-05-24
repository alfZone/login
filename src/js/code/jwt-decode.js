"use strict";
function e(e) {
    let t = e.replace(/-/g, "+").replace(/_/g, "/");
    switch (t.length % 4) {
        case 0:
            break;
        case 2:
            t += "==";
            break;
        case 3:
            t += "=";
            break;
        default:
            throw new Error("base64 string is not of the correct length");
    }
    try {
        return (function (e) {
            return decodeURIComponent(
                atob(e).replace(/(.)/g, function (e, t) {
                    let r = t.charCodeAt(0).toString(16).toUpperCase();
                    return r.length < 2 && (r = "0" + r), "%" + r;
                })
            );
        })(t);
    } catch (e) {
        return atob(t);
    }
}
class t extends Error {
    constructor(e) {
        super(e);
    }
}
function r(r, n = { header: !1 }) {
    if ("string" != typeof r) throw new t("Invalid token specified: must be a string");
    const o = !0 === (n = n || {}).header ? 0 : 1,
        a = r.split(".")[o];
    if ("string" != typeof a) throw new t("Invalid token specified: missing part #" + (o + 1));
    let s;
    try {
        s = e(a);
    } catch (e) {
        throw new t("Invalid token specified: invalid base64 for part #" + (o + 1) + " (" + e.message + ")");
    }
    try {
        return JSON.parse(s);
    } catch (e) {
        throw new t("Invalid token specified: invalid json for part #" + (o + 1) + " (" + e.message + ")");
    }
}
t.prototype.name = "InvalidTokenError";
const n = r;
(n.default = r), (n.InvalidTokenError = t), (module.exports = n);
//# sourceMappingURL=jwt-decode.js.map
