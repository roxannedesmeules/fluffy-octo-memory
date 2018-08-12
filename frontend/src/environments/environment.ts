// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.

export const environment = {
    production : false,
    version    : "0.1.0",

    api : {
        url          : "http://api.blog.local/v1/",
        client_token : "2U82q14bSuZwF-ChZzvh-35-SzK1vlT4",
    },

    socialMedia : {
        facebook  : "https://facebook.com",
        twitter   : "https://twitter.com/mlle_desmeules",
        instagram : "https://instagram.com/mlle_desmeules",
        github    : "https://github.com/mlleDesmeules",
    },
};
