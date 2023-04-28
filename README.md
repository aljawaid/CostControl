# CostControl
![GitHub All Releases](https://img.shields.io/github/downloads/aljawaid/CostControl/total?style=for-the-badge "GitHub All Downloads") - **_A Plugin for [Kanboard](https://github.com/kanboard/kanboard "Kanboard - Kanban Project Management Software")_** 

Use the new Cost Control section to enable currencies and budgeting in Kanboard. Get live currency rates automatically for over 120 currencies allowing users to compare with manually saved rates. This plugin replaces and extends the features from the original [Budget](https://github.com/kanboard/plugin-budget) plugin enabling projects to have an associated cost element.


## Features

- Introducing a new Cost Control section allowing access for all users to use and update all currency rates
  - Access directly from the dashboard or from the user dropdown menu
  - New menu template hook for developers: `template:cost-control:sidebar`
- Quickly filter through all the currencies
- _Application Currency_ is known as Base Currency (can only be edited by `admins`)
- Save a favorite currency as _Reference Currency_ (can only be edited by `admins`)
- Choose from **over 120 currencies** in your projects
  - **101** New currencies in addition to the 25 standard default currencies
  - All currency codes are [ISO 4217 three letter currency format](https://en.wikipedia.org/wiki/ISO_4217 "Learn more")
  - Includes `XDR` - International Monetary Fund (IMF) Special Drawing Rights
- **Budget Management** - _Budget plugin imported feature_
  - Budget management is based on subtask time tracking, the user timetable and the user hourly rate
- **Budget Lines** - _Budget plugin imported feature_
  - Budget lines are used to define a budget for the project
  - Budgets can be adjusted by adding a new entry with an effective date
- **Cost Breakdown** - _Budget plugin imported feature_
  - Individual costs of each subtask are shown
  - Time spent is rounded to the nearest quarter
- **Budget Chart** - _Budget plugin imported feature_
  - Show expenses representing user costs
  - Show budget lines as the provisioned budget
  - Display the remaining budget at any given time
- **User Hourly Rate** - _Budget plugin imported feature_
  - Each user can have a set hourly rate in `User Profile` &#10562; `Hourly Rate`
  - This feature is used for budget calculation
  - Each hourly rate shows an effective date with different currencies
- **Live Currency Rates**
  - Live updated currency rates are retrieved daily from [ExchangeRate-API](https://www.exchangerate-api.com)
  - Show the last updated time when each currency was updated (except for currencies with a rate of `1.0`)
  - Show an alert notification to inform the user of the next update
- **Manual Currency Rates**
  - Show the last modified date for manual currency rates
  - Add a comment for each manual rate for easy reference
    - Replace previous comments directly when modifying manual rates
  - Add and edit comments individually


## Screenshots

**Cost Control**
![Application Currencies](../master/Screenshots/screenshot-exchange-rates.png)

**Reference Currency with Manual and Live Rates**
![Reference Currency with Manual and Live Rates](../master/Screenshots/screenshot-app-currencies.png)

**All Rates**
![Reference Currency with Manual and Live Rates](../master/Screenshots/screenshot-rates-list.png)

**Options**
![Options](../master/Screenshots/screenshot-options.png)

**Settings**
![Settings](../master/Screenshots/screenshot-settings.png)

**Budget Lines**  
![Cost Lines](https://cloud.githubusercontent.com/assets/323546/20451620/965a4a2e-adc9-11e6-9131-3088ce6d8d78.png "Budget plugin imported feature")

**Cost Breakdown**  
![Cost Breakdown](https://cloud.githubusercontent.com/assets/323546/20451619/9658c9ba-adc9-11e6-8dd9-97b7d01db7f2.png "Budget plugin imported feature")

**Budget Graph**  
![Budget Graph](https://cloud.githubusercontent.com/assets/323546/20451621/965c1110-adc9-11e6-925c-c37c5a738c26.png "Budget plugin imported feature")

**Hourly Rate**  
![Hourly Rate](https://cloud.githubusercontent.com/assets/323546/20451622/965da606-adc9-11e6-9537-cd987abac06d.png "Budget plugin imported feature")


## Usage

Go to `Project` &#10562; Budget  

Go to `Settings` &#10562; Currency Rates

Go to `Dashboard` &#10562; Cost Control


## Installation & Compatibility

<details>
    <summary><strong>Installation</strong></summary>

- Install via the **[Kanboard](https://github.com/kanboard/kanboard "Kanboard - Kanban Project Management Software") Plugin Directory** or see [INSTALL.md](../master/INSTALL.md)
- Read the full [**Changelog**](../master/changelog.md "See changes") to see the latest updates

</details>
<details>
    <summary><strong>Compatibility</strong></summary>

- Requires [Kanboard](https://github.com/kanboard/kanboard "Kanboard - Kanban Project Management Software") ≥`1.2.20`
- **Other Plugins & Action Plugins**
  - Compatible with [URLCleaner](https://github.com/aljawaid/URLCleaner), [PluginManager](https://github.com/aljawaid/PluginManager)
  - **Migrating data from the [Budget](https://github.com/kanboard/plugin-budget) plugin**
    - Uninstall the Budget plugin
      - _The data in the database is not deleted by default_
    - Install the CostControl plugin
      - _Database tables for the imported features are identical therefore data should be preserved_
      - _The clean URLs will change therefore any saved bookmarks must be updated_
- **Core Files & Templates**
  - `03` Template overrides
  - Database Changes:
    - `01` New database table created as `budget_lines`
    - `01` New database table created as `hourly_rates`
    - `04` New columns added to the `currencies` table as `last_modified`, `comment`, `live_rate`, `live_rate_updated`

</details>
<details>
    <summary><strong>Translations</strong></summary>

- _Starter template available_

</details>
 

## Authors & Contributors

- [@aljawaid](https://github.com/aljawaid) - Author
- [Craig Crosby](https://github.com/creecros) - Contributor
- [Frédéric Guillot](https://github.com/kanboard/plugin-budget) - Author (imported features from the Budget plugin)
- _Contributors welcome_


## License

- This project is distributed under the [MIT License](../master/LICENSE "Read The MIT license")
