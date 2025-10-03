// Конфигурация тулбара
const toolbarOptions = [
    [{ 'header': [2,3,4,5,6] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{'align': ['', 'center', 'right', 'justify']}],
    [{'script': 'sub'}, {'script': 'super'}],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    ['blockquote', 'code-block', 'code'],
    ['link', 'image'],
    ['clean']
];

const icons = Quill.import('ui/icons');
icons['code-block'] = '<i class="fa fa-file-code"></i>';

// Инициализация Quill
const quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
        syntax: true,
        toolbar: toolbarOptions
    },
});

// Обновление скрытого поля
function updateHiddenContent() {
    let content = quill.root.innerHTML;
    content = removeSelectElement(content);

    document.getElementById('hidden-content').value = content;
}

function removeSelectElement(html) {
    const pattern = /<select class="ql-ui" contenteditable="false">.*<\/select>/g;
    return html.replace(pattern, '');
}

// Обработчики событий
quill.on('text-change', updateHiddenContent);

document.getElementById('pageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    updateHiddenContent();
    this.submit();
});

// Копирование кода
window.copyCode = function(button) {
    const code = button.closest('.code-block').querySelector('pre').innerText;
    navigator.clipboard.writeText(code);
};
