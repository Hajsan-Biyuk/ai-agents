"""П-1: в формах переставить блок согласия (div.checkbox) над кнопкой отправки.
Использование: python3 swap_checkbox.py file.php [...]  — правит файлы на месте, печатает число перестановок."""
import re
import sys

# строка с кнопкой, сразу за ней блок <div class="checkbox"> ... </div> того же отступа
PAT = re.compile(r'(?P<btn>(?P<ind>\t*)<button[^\r\n]*</button>\r?\n)(?P<chk>(?P=ind)<div class="checkbox">\r?\n(?:(?!(?P=ind)</div>\r?\n).*\n)*?(?P=ind)</div>\r?\n)')

for path in sys.argv[1:]:
    src = open(path, encoding='utf-8', newline='').read()
    out, n = PAT.subn(lambda m: m['chk'] + m['btn'], src)
    assert sorted(out) == sorted(src), path  # только перестановка, ни одного символа не потеряно
    open(path, 'w', encoding='utf-8', newline='').write(out)
    print(n, path)
