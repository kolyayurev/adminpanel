{{-- Просмотр записи для модалки: то же тело, что и на странице, без обвязки layouts.master --}}
@include($dataType->getShowBodyView())
{{-- Тело может рисовать Vue-инстансы через @push('vue'); на странице их выводит общий
     @stack('vue') из layouts/master.blade.php, в ответе модалки своего стека нет. --}}
@stack('vue')
