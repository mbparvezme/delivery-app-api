import Link from 'next/link';
import MapLoader from '@/components/MapLoader'; // Import the new client component

export default function MapPage() {
  return (
    <main className="min-h-screen bg-slate-50 p-4 sm:p-6 lg:p-8">
      <div className="max-w-7xl mx-auto">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-3xl font-bold text-slate-800">Zone Management</h1>
          <Link href="/" className="text-sm font-medium text-sky-600 hover:text-sky-800">
            &larr; Back to Dashboard
          </Link>
        </div>
        {/* Render the MapLoader component here */}
        <MapLoader />
      </div>
    </main>
  );
}