// get version from package.json and update files: readme.txt, main plugin header Version, and LEMON_FAQ_VERSION constant

const fs = require('fs');
const path = require('path');
const packageJson = require('../package.json');

const version = packageJson.version;

// Paths
const readmePath = path.resolve(__dirname, '../readme.txt');
const pluginFilePath = path.resolve(__dirname, '../lemon-woo.php');

// 1) Update readme.txt Stable tag
if (fs.existsSync(readmePath)) {
   const readmeContent = fs.readFileSync(readmePath, 'utf8');
   const newReadme = readmeContent.replace(/Stable tag:\s*\d+\.\d+(?:[\w.-]*)/i, `Stable tag: ${version}`);
   fs.writeFileSync(readmePath, newReadme);
   console.log(`Updated Stable tag in readme.txt to ${version}`);
} else {
   console.warn(`readme.txt not found at ${readmePath}, skipping`);
}

// 2) Update plugin header Version (rule 11) in main plugin file (lemon-faq.php)
if (fs.existsSync(pluginFilePath)) {
   let pluginContent = fs.readFileSync(pluginFilePath, 'utf8');

   // Update the plugin header 'Version:' line. Use a robust regex that matches the header field.
   const headerVersionRegex = /(^\s*\*\s*Version:\s*)([0-9A-Za-z.\-+_]+)(\s*)$/m;
   if (headerVersionRegex.test(pluginContent)) {
      pluginContent = pluginContent.replace(headerVersionRegex, `$1${version}$3`);
      console.log(`Updated plugin header Version to ${version}`);
   } else {
      console.warn('Plugin header Version line not found, skipping header update');
   }

   // Update the LEMON_WOO_VERSION constant definition.
   const constRegex = /(define\(\'LEMON_WOO_VERSION\'\s*,\s*\')([0-9A-Za-z.\-+_]+)(\'\s*\)\s*;)/;
   if (constRegex.test(pluginContent)) {
      pluginContent = pluginContent.replace(constRegex, `$1${version}$3`);
      console.log(`Updated LEMON_WOO_VERSION constant to ${version}`);
   } else {
      console.warn('LEMON_WOO_VERSION constant not found, skipping constant update');
   }

   fs.writeFileSync(pluginFilePath, pluginContent, 'utf8');
} else {
   console.warn(`Plugin file not found at ${pluginFilePath}, skipping plugin updates`);
}

console.log(`Bump complete. Version is now ${version}`);