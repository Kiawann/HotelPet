const AnimalCategoryChart = () => {
    const [data, setData] = React.useState([]);
    const [loading, setLoading] = React.useState(true);

    React.useEffect(() => {
        fetch('/animal-category-stats')
            .then(response => response.json())
            .then(data => {
                setData(data);
                setLoading(false);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                setLoading(false);
            });
    }, []);

    if (loading) {
        return <div>Loading...</div>;
    }

    const colors = {
        'Kucing': '#8884d8',
        'Anjing': '#82ca9d',
        'Burung': '#ffc658',
        'Kelinci': '#ff7300'
    };

    return React.createElement('div', { className: 'card shadow-lg p-4' },
        React.createElement('h4', { className: 'mb-4' }, 'Statistik Kategori Hewan'),
        React.createElement(Recharts.ResponsiveContainer, { width: '100%', height: 400 },
            React.createElement(Recharts.LineChart, { data: data },
                React.createElement(Recharts.CartesianGrid, { strokeDasharray: '3 3' }),
                React.createElement(Recharts.XAxis, { dataKey: 'month' }),
                React.createElement(Recharts.YAxis),
                React.createElement(Recharts.Tooltip),
                React.createElement(Recharts.Legend),
                Object.keys(colors).map(category =>
                    React.createElement(Recharts.Line, {
                        key: category,
                        type: 'monotone',
                        dataKey: category,
                        stroke: colors[category],
                        strokeWidth: 2
                    })
                )
            )
        )
    );
};

// Render the chart
ReactDOM.render(
    React.createElement(AnimalCategoryChart),
    document.getElementById('animal-category-chart')
);