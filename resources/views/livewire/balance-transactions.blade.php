<div>
    <h2>Balance Transactions</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->amount }}</td>
                    <td>{{ $transaction->type }}</td>
                    <td>{{ $transaction->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
