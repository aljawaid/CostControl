// Lints CostControl browser assets. Related coverage: tests/maintenance_contract.php.

export default [
    {
        files: ["Assets/js/**/*.js"],
        languageOptions: {
            ecmaVersion: "latest",
            sourceType: "script",
            globals: {
                c3: "readonly",
                d3: "readonly",
                document: "readonly",
                JSON: "readonly",
                window: "readonly"
            }
        },
        rules: {
            "no-undef": "error",
            "no-unused-vars": "error",
            "no-var": "error",
            "prefer-const": "error"
        }
    }
];
