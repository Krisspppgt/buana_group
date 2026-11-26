<x-filament-panels::page>
    @php
        $report = $this->getReportData();
        $categories = \App\Models\Expense::categories();
        $isProfit = $report['net_profit'] >= 0;
    @endphp

    {{-- Filter Periode & Unit Usaha --}}
    <x-filament::section>
        <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 1rem;">
            <div style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.375rem; opacity: 0.8;">
                        Dari Tanggal
                    </label>
                    <x-filament::input.wrapper>
                        <x-filament::input type="date" wire:model.live="startDate" />
                    </x-filament::input.wrapper>
                </div>

                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.375rem; opacity: 0.8;">
                        Sampai Tanggal
                    </label>
                    <x-filament::input.wrapper>
                        <x-filament::input type="date" wire:model.live="endDate" />
                    </x-filament::input.wrapper>
                </div>

                <div style="min-width: 210px;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.375rem; opacity: 0.8;">
                        Unit Usaha
                    </label>
                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.live="unitFilter">
                            <option value="all">Semua Unit Usaha</option>
                            @foreach (\App\Enums\Unit::cases() as $unit)
                                <option value="{{ $unit->value }}">{{ $unit->label() }}</option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                <x-filament::button size="sm" color="gray" wire:click="setPreset('today')">Hari Ini</x-filament::button>
                <x-filament::button size="sm" color="gray" wire:click="setPreset('this_month')">Bulan Ini</x-filament::button>
                <x-filament::button size="sm" color="gray" wire:click="setPreset('last_30_days')">30 Hari</x-filament::button>
                <x-filament::button size="sm" color="gray" wire:click="setPreset('this_year')">Tahun Ini</x-filament::button>
            </div>
        </div>
    </x-filament::section>

    {{-- Ringkasan KPI 4 Kolom --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
        <x-filament::section>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="font-size: 0.8125rem; font-weight: 500; opacity: 0.75;">Total Pendapatan</span>
                <x-filament::badge color="gray">Omzet</x-filament::badge>
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; font-variant-numeric: tabular-nums;">
                Rp {{ number_format($report['total_revenue'], 0, ',', '.') }}
            </div>
            <div style="margin-top: 0.375rem; font-size: 0.75rem; opacity: 0.7;">
                Seluruh transaksi non-batal pada periode
            </div>
        </x-filament::section>

        <x-filament::section>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="font-size: 0.8125rem; font-weight: 500; opacity: 0.75;">Laba Kotor</span>
                <x-filament::badge color="success">Margin {{ $report['gross_margin'] }}%</x-filament::badge>
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #059669; font-variant-numeric: tabular-nums;">
                Rp {{ number_format($report['gross_profit'], 0, ',', '.') }}
            </div>
            <div style="margin-top: 0.375rem; font-size: 0.75rem; opacity: 0.7;">
                HPP: Rp {{ number_format($report['total_cogs'], 0, ',', '.') }}
            </div>
        </x-filament::section>

        <x-filament::section>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="font-size: 0.8125rem; font-weight: 500; opacity: 0.75;">Biaya & Kerugian</span>
                <x-filament::badge color="warning">Beban</x-filament::badge>
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #d97706; font-variant-numeric: tabular-nums;">
                Rp {{ number_format($report['total_expenses'], 0, ',', '.') }}
            </div>
            <div style="margin-top: 0.375rem; font-size: 0.75rem; opacity: 0.7;">
                Operasional, logistik, & barang rusak
            </div>
        </x-filament::section>

        <x-filament::section>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="font-size: 0.8125rem; font-weight: 500; opacity: 0.75;">
                    {{ $isProfit ? 'Laba Bersih' : 'Rugi Bersih' }}
                </span>
                <x-filament::badge :color="$isProfit ? 'success' : 'danger'">
                    {{ $isProfit ? 'Profit' : 'Loss' }}
                </x-filament::badge>
            </div>
            <div style="font-size: 1.5rem; font-weight: 700; color: {{ $isProfit ? '#059669' : '#e11d48' }}; font-variant-numeric: tabular-nums;">
                Rp {{ number_format($report['net_profit'], 0, ',', '.') }}
            </div>
            <div style="margin-top: 0.375rem; font-size: 0.75rem; opacity: 0.7;">
                Laba Kotor dikurangi Biaya Operasional
            </div>
        </x-filament::section>
    </div>

    {{-- Rincian Laporan & Ringkasan Unit Usaha --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.25rem; align-items: start;">
        <x-filament::section heading="Rincian Laba & Rugi" description="Periode {{ $report['start']->format('d M Y') }} – {{ $report['end']->format('d M Y') }}">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <tbody>
                    <tr style="border-bottom: 1px solid rgba(156, 163, 175, 0.2);">
                        <td style="padding: 0.625rem 0; opacity: 0.85;">Pendapatan Produk (Air, Food, Konveksi)</td>
                        <td style="padding: 0.625rem 0; text-align: right; font-weight: 500; font-variant-numeric: tabular-nums;">
                            Rp {{ number_format($report['order_revenue'], 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(156, 163, 175, 0.2);">
                        <td style="padding: 0.625rem 0; opacity: 0.85;">Pendapatan Tour & Travel</td>
                        <td style="padding: 0.625rem 0; text-align: right; font-weight: 500; font-variant-numeric: tabular-nums;">
                            Rp {{ number_format($report['tour_revenue'], 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(156, 163, 175, 0.2);">
                        <td style="padding: 0.625rem 0; opacity: 0.85;">Pendapatan Ticketing</td>
                        <td style="padding: 0.625rem 0; text-align: right; font-weight: 500; font-variant-numeric: tabular-nums;">
                            Rp {{ number_format($report['ticket_revenue'], 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(156, 163, 175, 0.35);">
                        <td style="padding: 0.75rem 0; font-weight: 700;">Total Pendapatan Usaha</td>
                        <td style="padding: 0.75rem 0; text-align: right; font-weight: 700; font-variant-numeric: tabular-nums;">
                            Rp {{ number_format($report['total_revenue'], 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(156, 163, 175, 0.2); color: #e11d48;">
                        <td style="padding: 0.625rem 0;">Harga Pokok Penjualan (HPP)</td>
                        <td style="padding: 0.625rem 0; text-align: right; font-weight: 500; font-variant-numeric: tabular-nums;">
                            -Rp {{ number_format($report['total_cogs'], 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(156, 163, 175, 0.35); color: #059669;">
                        <td style="padding: 0.75rem 0; font-weight: 700;">Laba Kotor (Gross Profit)</td>
                        <td style="padding: 0.75rem 0; text-align: right; font-weight: 700; font-variant-numeric: tabular-nums;">
                            Rp {{ number_format($report['gross_profit'], 0, ',', '.') }}
                        </td>
                    </tr>

                    @forelse ($report['expenses_by_category'] as $cat => $amount)
                        <tr style="border-bottom: 1px solid rgba(156, 163, 175, 0.2); color: #d97706;">
                            <td style="padding: 0.625rem 0;">Biaya: {{ $categories[$cat] ?? ucfirst($cat) }}</td>
                            <td style="padding: 0.625rem 0; text-align: right; font-weight: 500; font-variant-numeric: tabular-nums;">
                                -Rp {{ number_format($amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr style="border-bottom: 1px solid rgba(156, 163, 175, 0.2);">
                            <td colspan="2" style="padding: 0.625rem 0; font-size: 0.75rem; opacity: 0.6;">
                                Belum ada catatan biaya operasional pada periode ini.
                            </td>
                        </tr>
                    @endforelse

                    <tr>
                        <td style="padding: 0.875rem 0 0.25rem; font-size: 1rem; font-weight: 700; color: {{ $isProfit ? '#059669' : '#e11d48' }};">
                            {{ $isProfit ? 'Laba Bersih' : 'Rugi Bersih' }}
                        </td>
                        <td style="padding: 0.875rem 0 0.25rem; text-align: right; font-size: 1rem; font-weight: 700; font-variant-numeric: tabular-nums; color: {{ $isProfit ? '#059669' : '#e11d48' }};">
                            Rp {{ number_format($report['net_profit'], 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </x-filament::section>

        <x-filament::section heading="Ringkasan per Unit Usaha" description="Kontribusi omzet, HPP, biaya, dan laba bersih per unit">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(156, 163, 175, 0.35); font-size: 0.75rem; text-transform: uppercase; opacity: 0.7;">
                            <th style="padding: 0.5rem 0.5rem 0.625rem 0; text-align: left;">Unit Usaha</th>
                            <th style="padding: 0.5rem 0.5rem 0.625rem; text-align: right;">Omzet</th>
                            <th style="padding: 0.5rem 0.5rem 0.625rem; text-align: right;">HPP</th>
                            <th style="padding: 0.5rem 0.5rem 0.625rem; text-align: right;">Biaya</th>
                            <th style="padding: 0.5rem 0 0.625rem 0.5rem; text-align: right;">Net</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['unit_breakdown'] as $row)
                            <tr style="border-bottom: 1px solid rgba(156, 163, 175, 0.2);">
                                <td style="padding: 0.625rem 0.5rem 0.625rem 0; font-weight: 600;">{{ $row['unit'] }}</td>
                                <td style="padding: 0.625rem 0.5rem; text-align: right; font-variant-numeric: tabular-nums;">
                                    Rp {{ number_format($row['revenue'], 0, ',', '.') }}
                                </td>
                                <td style="padding: 0.625rem 0.5rem; text-align: right; opacity: 0.7; font-variant-numeric: tabular-nums;">
                                    Rp {{ number_format($row['cogs'], 0, ',', '.') }}
                                </td>
                                <td style="padding: 0.625rem 0.5rem; text-align: right; color: #d97706; font-variant-numeric: tabular-nums;">
                                    Rp {{ number_format($row['expenses'], 0, ',', '.') }}
                                </td>
                                <td style="padding: 0.625rem 0 0.625rem 0.5rem; text-align: right; font-weight: 700; font-variant-numeric: tabular-nums; color: {{ $row['net'] >= 0 ? '#059669' : '#e11d48' }};">
                                    Rp {{ number_format($row['net'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
