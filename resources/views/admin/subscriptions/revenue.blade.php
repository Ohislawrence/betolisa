<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Revenue Report
            </h2>
            <a href="{{ route('admin.subscriptions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                &larr; Back to Subscriptions
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h4 class="text-sm font-medium text-gray-500">Total Revenue (All Time)</h4>
                    <p class="text-3xl font-bold text-blue-600 mt-1">&#8358;{{ number_format($totalRevenue) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h4 class="text-sm font-medium text-gray-500">This Month's Revenue</h4>
                    <p class="text-3xl font-bold text-green-600 mt-1">&#8358;{{ number_format($thisMonthRevenue) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h4 class="text-sm font-medium text-gray-500">New Subscriptions This Month</h4>
                    <p class="text-3xl font-bold text-purple-600 mt-1">{{ $thisMonthSubscriptions }}</p>
                </div>
            </div>

            {{-- Period Filter --}}
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <form action="{{ route('admin.subscriptions.revenue') }}" method="GET" class="flex items-center gap-4">
                    <label class="text-sm font-medium text-gray-700">Group by:</label>
                    @foreach(['daily' => 'Daily (Last 30 Days)', 'weekly' => 'Weekly (Last 12 Weeks)', 'monthly' => 'Monthly (Last 12 Months)', 'yearly' => 'Yearly (Last 5 Years)'] as $value => $label)
                        <a href="{{ route('admin.subscriptions.revenue', ['period' => $value]) }}"
                            class="px-4 py-2 rounded text-sm font-medium transition-colors
                                {{ $period === $value
                                    ? 'bg-purple-600 text-white'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </form>
            </div>

            {{-- Revenue Table --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Revenue Breakdown
                        <span class="text-sm font-normal text-gray-500 ml-2">({{ ucfirst($period) }})</span>
                    </h3>
                </div>

                @if($transactions->isEmpty())
                    <div class="px-6 py-12 text-center text-gray-500">
                        No transaction data found for this period.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Period
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Transactions
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Revenue
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Avg per Transaction
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php $grandTotal = 0; $grandCount = 0; @endphp
                                @foreach($transactions as $row)
                                    @php
                                        $grandTotal += $row->total;
                                        $grandCount += $row->count;
                                        $periodLabel = $row->date ?? $row->month ?? $row->year
                                            ?? ($row->week ? 'Week '.$row->week : 'N/A');
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $periodLabel }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ number_format($row->count) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-700">
                                            &#8358;{{ number_format($row->total) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            &#8358;{{ $row->count > 0 ? number_format($row->total / $row->count) : '0' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                                <tr>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">Total</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ number_format($grandCount) }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-green-700">&#8358;{{ number_format($grandTotal) }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-600">
                                        &#8358;{{ $grandCount > 0 ? number_format($grandTotal / $grandCount) : '0' }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
