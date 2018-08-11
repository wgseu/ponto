import os
import sys
import re
import io

walk_dir = sys.argv[1]
src_dir = sys.argv[2]

print('walk_dir = ' + walk_dir)

# If your current working directory may change during script execution, it's recommended to
# immediately convert program arguments to an absolute path. Then the variable root below will
# be an absolute path as well. Example:
# walk_dir = os.path.abspath(walk_dir)
print('walk_dir (absolute) = ' + os.path.abspath(walk_dir))
pattern = r'\s*return require\(\$this->getApplication\(\)->getPath\(\'pages\'\) \. \'([^\']+)\'\);\n'
for root, subdirs, files in os.walk(walk_dir):
  for filename in files:
    file_path = os.path.join(root, filename)
    if not re.search('(?:OldApi|Page)Controller.php$', filename):
      continue
    print('File: ', file_path)
    with io.open(file_path, 'r', encoding='utf8') as f:
      content = f.read()
    while True:
      m = re.search(pattern, content, flags = re.M)
      if not m:
        break
      src_filename = m.group(1)
      source_path = os.path.join(src_dir, src_filename[1:])
      repl = '\n'
      usestmt = ''
      if os.path.isfile(source_path):
        with io.open(source_path, 'r', encoding='utf8') as f:
          repl = f.read()
        # os.remove(source_path)
        repl = re.sub(r'<\?php\n/\*\*.*\*/\n', '\n', repl, count=1, flags = re.M + re.DOTALL)
        uses = re.search(r'((?:^use .*$\n)+)', repl, flags = re.M)
        if uses:
          usestmt = uses.group(1)
        repl = re.sub(r'(?:^use .*$\n)+', '', repl, flags = re.M)
        repl = re.sub(r'^(.+)$', r'        \1', repl, flags = re.M)
      namespc = re.search(r'namespace ([^;]+);\n', content, flags = re.M).group(1)
      namespc = re.escape(namespc)
      usestmt = re.sub(r'^use ' + namespc + r'\\.*$\n', '', usestmt, flags = re.M)
      content = re.sub(r'(namespace [^;]+;\n)', r'\1' + usestmt, content, count=1, flags = re.M)
      content = re.sub(pattern, repl, content, count=1, flags = re.M)
    content = re.sub(r'(?:^use MZ\\System\\Permissao;\n){2,}', r'use MZ\\System\\Permissao;\n', content, flags = re.M)
    content = re.sub(r'(namespace [^;]+;\n)([^\n])', r'\1\n\2', content, flags = re.M)
    # with io.open(file_path, 'w', encoding='utf8', newline='\n') as f:
    #   f.write(content)
    print(content)
