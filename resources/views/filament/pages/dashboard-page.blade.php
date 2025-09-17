<x-filament::page>
    <!-- Dashboard Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        {{-- Use Filament's widget component to render all widgets --}}
        <x-filament-widgets::widgets
            :columns="$this->getColumn()"
            :widgets="$this->getWidgets()"
        />
    </div>
</x-filament::page>