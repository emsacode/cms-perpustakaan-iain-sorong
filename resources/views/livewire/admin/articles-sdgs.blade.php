<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-border pb-5">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">SDGs Taksonomi</h1>
            <p class="text-xs text-muted-foreground mt-1">Pemetaan kontribusi publikasi ilmiah dan berita perpustakaan terhadap Sustainable Development Goals (SDGs) PBB.</p>
        </div>
        <a href="{{ route('admin.articles') }}" class="text-xs font-semibold px-3 py-1.5 bg-secondary text-secondary-foreground rounded-lg border border-border hover:bg-accent transition-colors">
            Kembali ke Semua Pos
        </a>
    </div>

    <!-- SDGs List Table -->
    <div class="bg-card text-card-foreground border border-border rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-border bg-muted/40 text-muted-foreground font-medium">
                        <th class="p-4 w-28 text-xs font-semibold uppercase tracking-wider">Nomor Tujuan</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Nama SDGs Target (Bahasa Indonesia)</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider">Slug</th>
                        <th class="p-4 text-xs font-semibold uppercase tracking-wider text-right w-36">Jumlah Pos Terkait</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($sdgs as $s)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="p-4 font-mono text-xs font-bold text-foreground">
                                {{ explode(':', $s->name)[0] }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-3.5 h-3.5 bg-emerald-500 rounded-full border border-emerald-600 block shadow-sm"></span>
                                    <span class="font-semibold text-foreground text-sm">{{ $s->name }}</span>
                                </div>
                            </td>
                            <td class="p-4 font-mono text-xs text-muted-foreground">
                                {{ $s->slug }}
                            </td>
                            <td class="p-4 text-right font-mono text-xs text-foreground font-bold">
                                {{ $s->articles_count }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-xs text-muted-foreground">
                                Belum ada SDGs tag yang dimuat di database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sdgs->hasPages())
            <div class="p-4 border-t border-border bg-muted/10">
                {{ $sdgs->links() }}
            </div>
        @endif
    </div>
</div>
