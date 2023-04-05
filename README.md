# CostControl

#### _Plugin for [Kanboard](https://github.com/kanboard/kanboard "Kanboard - Kanban Project Management Software")_

Manage your costs per project to budget and plan using over 120 currencies. Get automatic live daily updates to compare your saved currency rates to forecast your budgets more accurately. This plugin replaces and extends the features from the original [Budget](https://github.com/kanboard/plugin-budget) plugin. Extended features include allowing users to change and modify their base currencies for most countries. 


Features
-------------

- Choose from **over 120 currencies** in your projects
  - **101** New currencies in addition to the 25 standard default currencies
  - All currency codes are [ISO 4217 three letter currency format](https://en.wikipedia.org/wiki/ISO_4217 "Learn more")
  - Includes `XDR` - International Monetary Fund (IMF) Special Drawing Rights
- Live updated currency rates retrieved daily from [ExchangeRate-API](https://www.exchangerate-api.com)
- Show the last modified date for manual currency rates

#### Budget Management - _Budget plugin imported feature_
- Budget management is based on the subtask time tracking, the user timetable and the user hourly rate.

#### Budget Lines - _Budget plugin imported feature_
- Budget lines are used to define a budget for the project.
- The budget can be adjusted by adding a new entry with an effective date.

#### Cost Breakdown - _Budget plugin imported feature_
- Based on the subtask time tracking table and user information, the cost of each subtask is shown.
- The time spent is rounded to the nearest quarter.

#### Budget Chart - _Budget plugin imported feature_
- Graph shows:
  - Expenses representing user costs
  - Budget lines as the provisioned budget
  - Remaining budget at any given time

#### Hourly Rate - _Budget plugin imported feature_
- Each user can have a set hourly rate.
- This feature is used for budget calculation.
  - To define a new price, go to **User profile > Hourly rates**.
- Each hourly rate shows an effective date with different currencies.


Screenshots
----------

**Budget Lines**  
![Cost Lines](https://cloud.githubusercontent.com/assets/323546/20451620/965a4a2e-adc9-11e6-9131-3088ce6d8d78.png "Budget plugin imported feature")

**Cost Breakdown**  
![Cost Breakdown](https://cloud.githubusercontent.com/assets/323546/20451619/9658c9ba-adc9-11e6-8dd9-97b7d01db7f2.png "Budget plugin imported feature")

**Budget Graph**  
![Budget Graph](https://cloud.githubusercontent.com/assets/323546/20451621/965c1110-adc9-11e6-925c-c37c5a738c26.png "Budget plugin imported feature")

**Hourly Rate**  
![Hourly Rate](https://cloud.githubusercontent.com/assets/323546/20451622/965da606-adc9-11e6-9537-cd987abac06d.png "Budget plugin imported feature")


Usage
-------------

Go to `Project` &#10562; Budget  

Go to `Settings` &#10562; Currency Rates


Compatibility
-------------

- Requires [Kanboard](https://github.com/kanboard/kanboard "Kanboard - Kanban Project Management Software") ≥`1.0.37`

#### Other Plugins & Action Plugins
- Compatible with [URLCleaner](https://github.com/aljawaid/URLCleaner)
- **Migrating data from the [Budget](https://github.com/kanboard/plugin-budget) plugin**
  - Uninstall the Budget plugin
    - _The data in the database is not deleted by default_
  - Install the CostControl plugin
    - _Database tables for the imported features are identical therefore data should be preserved_
    - _The clean URLs will change therefore any saved bookmarks must be updated_
#### Core Files & Templates
- `03` template overrides
- Database Changes:
  - `01` New database table created as `budget_lines`
  - `01` New database table created as `hourly_rates`
  - `03` New columns added to the `currencies` table as `last_modified`, `live_rate`, `live_rate_updated`


Changelog
---------

Read the full [**Changelog**](../master/changelog.md "See changes")
 

Installation
------------

- **Install via the [Kanboard](https://github.com/kanboard/kanboard "Kanboard - Kanban Project Management Software") Plugin Directory**
  - _Go to:_
    - Kanboard: `Plugins` &#10562; `Plugin Directory`
  - _or with [PluginManager](https://github.com/aljawaid/PluginManager) installed:_
    - Kanboard: `Settings` &#10562; `Plugins` &#10562; `Plugin Directory`

**_or_**

- **Install via the [Releases](../master/Releases/ "A copy of each release is saved in the folder") folder**
  - A copy of each release is saved in the `/Releases` folder of the repository
  - Simply extract the `.zip` file into the `/plugins` directory

**_or_**

- **Install via [GitHub](https://github.com/ "Find the correct plugin from the list of repositories")**
  - Download the `.zip` file and decompress everything under the directory `/plugins`
  - The folder inside the `.zip` must not contain any branch names and must be exact case (matching the plugin name)

_Note: The `/plugins` folder is case-sensitive._

**_or_**

- **Install using Git CLI**
  - `git clone` (_or ftp upload_) and extract the `.zip` file into this folder: `.\plugins\` (must be exact case)


Translations
------------

- _Contributors welcome_
- _Starter template available_

Authors & Contributors
----------------------

- [@aljawaid](https://github.com/aljawaid) - Author
- [Frédéric Guillot](https://github.com/kanboard/plugin-budget) - Author (imported features from the Budget plugin)
- [Craig Crosby](https://github.com/creecros) - Contributor
- _Contributors welcome_


License
-------
- This project is distributed under the [MIT License](../master/LICENSE "Read The MIT license")
