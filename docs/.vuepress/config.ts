import { defineUserConfig } from 'vuepress'
import type { DefaultThemeOptions } from 'vuepress'

export default defineUserConfig<DefaultThemeOptions>({
  base: '/laravel-json-api/',
  lang: 'en-US',
  title: 'Laravel JSON:API',
  description: 'Integrate JSON:API resources on Laravel',

  plugins: [
    [
      '@vuepress/search', {
        searchMaxSuggestions: 10
      }
    ]
  ],

  theme: '@vuepress/theme-default',
  themeConfig: {
    logo: 'https://vuejs.org/images/logo.png',

    repo: 'skorelabs/laravel-json-api',

    navbar: [
      {
        text: 'Home',
        link: '/README.md',
      },
      {
        text: 'Guide',
        children: [
          {
            text: 'Installation',
            link: '/guide/README.md'
          },
          '/guide/usage.md',
          '/guide/testing.md',
          '/guide/implementations.md',
        ],
      },
      {
        text: 'Comparison',
        link: '/comparison.md',
      }
    ],

    sidebar: {
      '/guide/': [
        {
          text: 'Introduction',
          children: [
            {
              text: 'Installation',
              link: '/guide/README.md'
            },
            {
              text: 'Usage',
              link: '/guide/usage.md'
            },
            {
              text: 'Testing',
              link: '/guide/testing.md'
            },
            {
              text: 'Implementations',
              link: '/guide/implementations.md'
            },
          ]
        },
        // {
        //   text: 'Advanced topics',
        //   children: [
        //     {
        //       text: 'TypeScript',
        //       link: '/guide/typescript.md'
        //     },
        //   ]
        // },
        // {
        //   text: 'Upgrading',
        //   link: '/guide/upgrading.md'
        // },
      ],
    },
  },
})
