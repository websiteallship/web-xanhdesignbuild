/**
 * FTP Explorer - Kiểm tra cấu trúc thư mục trên server
 */
const ftp = require("basic-ftp");

async function explore() {
    const client = new ftp.Client();
    client.ftp.verbose = false;

    try {
        await client.access({
            host: "demo.xanhdesignbuild.vn",
            user: "admin@demo.xanhdesignbuild.vn",
            password: "123456@asdfg",
            secure: false,
        });

        // Check current directory
        const pwd = await client.pwd();
        console.log("📍 Current directory (pwd):", pwd);

        // List root
        console.log("\n📋 Contents of /:");
        const rootList = await client.list("/");
        for (const f of rootList) {
            console.log(`  ${f.isDirectory ? "📁" : "📄"} ${f.name}  (${f.size} bytes)`);
        }

        // Try going up one level
        console.log("\n📋 Contents of ../ (parent):");
        try {
            const parentList = await client.list("../");
            for (const f of parentList) {
                console.log(`  ${f.isDirectory ? "📁" : "📄"} ${f.name}`);
            }
        } catch (e) {
            console.log("  ❌ Cannot access parent:", e.message);
        }

        // Check if wp-content exists at root
        console.log("\n🔍 Looking for wp-content...");
        try {
            const wpList = await client.list("/wp-content");
            console.log("  ✅ Found /wp-content:");
            for (const f of wpList) {
                console.log(`    ${f.isDirectory ? "📁" : "📄"} ${f.name}`);
            }
        } catch (e) {
            console.log("  ❌ /wp-content not found");
        }

        // Check ../wp-content
        try {
            const wpList2 = await client.list("../wp-content");
            console.log("  ✅ Found ../wp-content:");
            for (const f of wpList2) {
                console.log(`    ${f.isDirectory ? "📁" : "📄"} ${f.name}`);
            }
        } catch (e) {
            console.log("  ❌ ../wp-content not found");
        }

        // Check ../wp-content/themes
        try {
            const themesList = await client.list("../wp-content/themes");
            console.log("  ✅ Found ../wp-content/themes:");
            for (const f of themesList) {
                console.log(`    ${f.isDirectory ? "📁" : "📄"} ${f.name}`);
            }
        } catch (e) {
            console.log("  ❌ ../wp-content/themes not found");
        }

    } catch (err) {
        console.error("❌ Error:", err.message);
    }

    client.close();
}

explore();
