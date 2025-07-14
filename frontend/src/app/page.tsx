import Link from 'next/link';

export default function HomePage() {
  return (
    <div className="flex items-center justify-center min-h-screen bg-slate-100">
      <div className="w-full max-w-md p-8 text-center bg-white rounded-lg shadow-md">
        <h1 className="text-2xl font-bold text-gray-900">Restaurant Dashboard</h1>
        <p className="mt-2 text-sm text-gray-600">Select an option to begin.</p>
        <div className="mt-6">
          <Link href="/map" className="inline-block w-full px-6 py-3 text-lg font-medium text-white bg-sky-600 rounded-md hover:bg-sky-700 transition-colors">
            Manage Delivery Zones
          </Link>
        </div>
      </div>
    </div>
  );
}