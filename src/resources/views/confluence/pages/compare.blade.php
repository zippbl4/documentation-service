@extends('confluence.layout')

@section('menu')
  @push('css')
    <style>
      .main-content {
        margin: 60px 0 40px 0;
      }
    </style>
  @endpush
@endsection

@push('css')
  <style>
    /* Контейнер сравнения */
    .compare-container {
      display: flex;
      gap: 20px;
      padding: 20px;
      overflow-x: auto;
    }

    /* Блок статьи */
    .article-block {
      background: white;
      border: 1px solid #DFE1E6;
      border-radius: 4px;
      min-width: 400px;
      width: 400px;
      display: flex;
      flex-direction: column;
      transition: 0.3s;
    }

    /* Заголовок блока */
    .article-header {
      padding: 16px;
      border-bottom: 1px solid #DFE1E6;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .article-title {
      font-size: 18px;
      font-weight: 600;
      color: #172B4D;
    }

    /* Контент статьи */
    .article-content {
      padding: 20px;
      overflow-y: auto;
      flex-grow: 1;
    }

    /* Кнопки управления */
    .control-bar {
      position: fixed;
      bottom: 20px;
      right: 20px;
      display: flex;
      gap: 10px;
      z-index: 1000;
    }

    .control-btn {
      background: #0052CC;
      color: white;
      border: none;
      padding: 12px 16px;
      border-radius: 4px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .close-btn {
      background: none;
      border: none;
      color: #6B778C;
      cursor: pointer;
      padding: 4px;
    }

    /* Стили контента как в Confluence */
    .article-content h1 {
      font-size: 28px;
      margin-bottom: 20px;
      border-bottom: 2px solid #DFE1E6;
      padding-bottom: 10px;
    }

    .article-content p {
      line-height: 1.6;
      margin-bottom: 16px;
    }

    .article-content .code-block {
      background: #F4F5F7;
      border: 1px solid #DFE1E6;
      border-radius: 4px;
      padding: 16px;
      font-family: monospace;
      margin: 16px 0;
    }
  </style>
@endpush

@push('js')
  <script>
    // Счетчик для новых статей
    let articleCounter = <?= count($articles) ?>;

    // Добавление новой статьи
    function addNewArticle() {
      const container = document.getElementById('compareContainer');

      const newArticle = document.createElement('div');
      newArticle.className = 'article-block';
      newArticle.innerHTML = `
                <div class="article-header">
                    <h3>Новая статья ${++articleCounter}</h3>
                    <button class="delete-btn control-btn" onclick="removeArticle(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="article-content">
                    <div class="article-title" contenteditable="true">Новая статья</div>
                    <div class="article-text" contenteditable="true">
                        Начните вводить текст...
                    </div>
                </div>
            `;

      container.appendChild(newArticle);
    }

    // Удаление статьи
    function removeArticle(button) {
      const articleBlock = button.closest('.article-block');
      articleBlock.remove();
    }

    // Адаптивное изменение размеров
    function adjustBlocks() {
      const blocks = document.querySelectorAll('.article-block');
      blocks.forEach(block => {
        block.style.minWidth = `${window.innerWidth * 0.3}px`;
      });
    }

    window.addEventListener('resize', adjustBlocks);
    adjustBlocks();

    // Добавление новой статьи
    function addArticleBlock() {
      const template = document.getElementById('articleTemplate');
      const clone = template.content.cloneNode(true);
      const container = document.getElementById('compareContainer');

      // Добавляем анимацию
      clone.querySelector('.article-block').style.opacity = '0';
      container.appendChild(clone);

      // Анимированное появление
      setTimeout(() => {
        container.lastElementChild.style.opacity = '1';
        container.lastElementChild.scrollIntoView({ behavior: 'smooth' });
      }, 10);
    }

    // Удаление статьи
    function removeArticleBlock(btn) {
      const block = btn.closest('.article-block');
      block.style.transform = 'translateX(100%)';
      block.style.opacity = '0';

      setTimeout(() => block.remove(), 300);
    }

    // Инициализация при пустом контейнере
    <?php if(empty($articles)): ?>
    document.addEventListener('DOMContentLoaded', addArticleBlock);
    <?php endif; ?>
  </script>
@endpush

@push('top-menu')
  <!-- Панель управления -->
  <button class="create-btn" onclick="addArticleBlock()">
    <i class="fas fa-plus"></i>
    Сравнить
  </button>
@endpush

@section('body')
  <div class="compare-container" id="compareContainer">
    @foreach($articles as $article)
      <div class="article-block">
        <div class="article-header">
          <span class="article-title">{{ $article['title'] }}</span>
          <button class="close-btn" onclick="removeArticleBlock(this)">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="article-content">
          {!! $article['content'] !!}
        </div>
      </div>
    @endforeach
  </div>
@endsection
