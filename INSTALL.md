# Plugin Installation

- **Install via the [Kanboard](https://github.com/kanboard/kanboard "Kanboard - Kanban Project Management Software") Plugin Directory**
  - _Go to:_
    - Kanboard: `Plugins` &#10562; `Plugin Directory`
  - _or with [PluginManager](https://github.com/aljawaid/PluginManager "A Kanboard plugin") installed:_
    - Kanboard: `Settings` &#10562; `Plugins` &#10562; `Plugin Directory`
- **Install via the [Releases](../master/Releases/ "A copy of each release is saved in the folder") folder**
  - A copy of each release is saved in the `/Releases` folder of the repository
  - Simply extract the `.zip` file into the `/plugins` directory
- **Install via [GitHub](https://github.com/ "Find the correct plugin from the list of repositories")**
  - Download the `.zip` file and decompress everything under the directory `/plugins`
  - The folder inside the `.zip` must not contain any branch names and must be exact case (matching the plugin name)

- **Install using Git CLI**
  - `git clone` (_or ftp upload_) and extract the `.zip` file into this folder: `.\plugins\` (must be exact case)

_Note: The `/plugins` folder is case-sensitive._

## Daily live-rate refresh

Schedule the following command once per day, after Kanboard's normal `cronjob` command:

```shell
5 8 * * * cd /path/to/kanboard && ./cli cost-control:refresh-rates >/dev/null 2>&1
```

The command observes the provider's next-update time and keeps the last good rates if the provider is unavailable or returns invalid data. Administrators can run `./cli cost-control:refresh-rates --force` for a controlled immediate refresh. If no scheduler is configured, CostControl performs the same guarded refresh when the currency rates page is opened.

---

Read the full [**Changelog**](../master/changelog.md "See changes") or view the [**README**](../master/README.md "View README")
