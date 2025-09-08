import type {NextConfig} from 'next';

const nextConfig: NextConfig = {
  /* config options here */
  typescript: {
    ignoreBuildErrors: true,
  },
  eslint: {
    ignoreDuringBuilds: true,
  },
  images: {
    remotePatterns: [
      {
        protocol: 'https',
        hostname: 'placehold.co',
        port: '',
        pathname: '/**',
      }
    ],
  },
  env: {
    NEXT_PUBLIC_API_BASE_URL: 'http://172.16.33.36.:81/Practicas/syed/public/api',
    // NEXT_PUBLIC_API_BASE_URL: 'https://syed.joannesystem.com',
  }
};

export default nextConfig;
