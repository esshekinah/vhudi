import type { Config } from "tailwindcss";

export default {
  content: ["./src/**/*.{ts,tsx}"],
  theme: {
    extend: {
      colors: {
        vhudi: {
          bg: "#1f2a30",
          panel: "#000000",
          accent: "#8a6d3b",
        },
      },
    },
  },
  plugins: [],
} satisfies Config;
