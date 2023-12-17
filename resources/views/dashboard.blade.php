<x-admin-layout>
   <livewire:dashboard />

    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('dataLogin', (dataLogin) => {
                let optionsLogin = {
                    annotations: {
                        position: "back",
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    chart: {
                        type: "bar",
                        height: 300,
                    },
                    fill: {
                        opacity: 1,
                    },
                    plotOptions: {},
                    series: [
                        {
                            name: "Akses",
                            data: dataLogin[0]
                        },
                    ],
                    colors: "#435ebe",
                    xaxis: {
                        categories: [
                            "Jan",
                            "Feb",
                            "Mar",
                            "Apr",
                            "May",
                            "Jun",
                            "Jul",
                            "Aug",
                            "Sep",
                            "Oct",
                            "Nov",
                            "Dec",
                        ],
                    },
                }



                let chartLogin = new ApexCharts(
                    document.querySelector("#login-chart"),
                    optionsLogin
                )
                chartLogin.render()
            });
            Livewire.on('dataPermohonan', (dataPermohonan) => {
                let optionPermohonan = {
                    annotations: {
                        position: "back",
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    chart: {
                        type: "bar",
                        height: 300,
                    },
                    fill: {
                        opacity: 1,
                    },
                    plotOptions: {},
                    series: [
                        {
                            name: "Permohonan",
                            data: dataPermohonan[0]
                        },
                    ],
                    colors: "#435ebe",
                    xaxis: {
                        categories: [
                            "Jan",
                            "Feb",
                            "Mar",
                            "Apr",
                            "May",
                            "Jun",
                            "Jul",
                            "Aug",
                            "Sep",
                            "Oct",
                            "Nov",
                            "Dec",
                        ],
                    },
                }



                let chartLogin = new ApexCharts(
                    document.querySelector("#permohonan-chart"),
                    optionPermohonan
                )
                chartLogin.render()
            });
        });


    </script>
</x-admin-layout>
