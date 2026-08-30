<x-app-layout>

    <style>

        body {
            background: #f4f6f8;
            font-family: Arial, Helvetica, sans-serif;
        }

        .settings-container {
            max-width: 1000px;
            margin: auto;
            padding: 30px 20px;
        }

        .settings-header {
            background: white;
            border-left: 5px solid #174a7c;
            padding: 25px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
        }

        .settings-header h1 {
            margin: 0;
            color: #174a7c;
            font-size: 26px;
        }

        .settings-header p {
            color: #777;
            font-size: 13px;
        }

        .empty-settings {
            margin-top: 20px;
            background: white;
            padding: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 7px;
            color: #777;
        }

    </style>


    <div class="settings-container">

        <div class="settings-header">

            <h1>
                Settings
            </h1>

            <p>
                System settings will be added here in the future.
            </p>

        </div>


        <div class="empty-settings">

            <h3>
                Settings
            </h3>

            <p>
                This section is currently empty.
            </p>

        </div>

    </div>

</x-app-layout>