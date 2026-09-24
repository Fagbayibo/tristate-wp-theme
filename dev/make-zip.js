/**
 * Builds tristate-theme.zip, ready for WordPress → Appearance → Themes → Upload.
 *
 * Written with archiver rather than the OS zip tools: Windows' `tar -a` writes a
 * plain tar, and PowerShell's Compress-Archive stores backslash paths that fail
 * to extract on Linux hosts.
 */
const { ZipArchive } = require('archiver'); // archiver v8 exports named classes
const { createWriteStream, existsSync, unlinkSync } = require('fs');
const { join } = require('path');

const root = join(__dirname, '..');
const out = join(root, 'tristate-theme.zip');

if (existsSync(out)) unlinkSync(out);

const output = createWriteStream(out);
const archive = new ZipArchive({ zlib: { level: 9 } });

output.on('close', () => {
	console.log(`Created tristate-theme.zip (${(archive.pointer() / 1024 / 1024).toFixed(1)} MB)`);
});

archive.on('warning', (err) => console.warn(err));
archive.on('error', (err) => { throw err; });

archive.pipe(output);
// Everything under tristate-theme/, minus editor/OS noise.
archive.glob('tristate-theme/**/*', {
	cwd: root,
	dot: false,
	ignore: ['**/.DS_Store', '**/Thumbs.db', '**/*.map'],
});
archive.finalize();
