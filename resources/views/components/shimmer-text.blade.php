@props(['text', 'class' => ''])

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent animate-gradient-x border border-indigo-500/20 bg-indigo-500/5 {{ $class }}">
    {{ $text }}
</span>
