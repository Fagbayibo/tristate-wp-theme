/**
 * Publishes a theme release from this machine: `npm run release`.
 *
 * Reads Version from style.css, pushes main, builds tristate-theme.zip and
 * creates GitHub release v<Version> with the zip attached. The live site's
 * updater (inc/updater.php) then offers "Update now".
 *
 * Same job as .github/workflows/release.yml, for when Actions can't run.
 */
const { execSync } = require('child_process');
const { readFileSync } = require('fs');
const { join } = require('path');

const root = join(__dirname, '..');
const run = (cmd) => execSync(cmd, { cwd: root, stdio: 'inherit' });
const out = (cmd) => execSync(cmd, { cwd: root }).toString().trim();

const style = readFileSync(join(root, 'tristate-theme/style.css'), 'utf8');
const version = (style.match(/^Version:\s*(\S+)/m) || [])[1];
if (!/^\d+\.\d+\.\d+$/.test(version || '')) {
	throw new Error(`style.css Version must look like 1.0.1 (found "${version}")`);
}
const tag = `v${version}`;

if (out('git status --porcelain')) {
	console.error('Commit your changes first — the release must match what is on GitHub.');
	process.exit(1);
}

if (out(`git ls-remote --tags origin ${tag}`)) {
	console.error(`${tag} already exists. Bump Version in style.css (e.g. to the next patch number), commit, and run again.`);
	process.exit(1);
}

run('git push origin main');
run('node dev/make-zip.js');
run(`gh release create ${tag} tristate-theme.zip --target main --title "${tag}" --generate-notes`);

console.log(`\nReleased ${tag}. In WP admin: Dashboard → Updates → Check again.`);
