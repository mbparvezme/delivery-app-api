'use client';

import { useRouter } from 'next/navigation';

export default function LoginPage() {
  const router = useRouter();

  const handleLogin = (role: string) => {
    // In a real app, this would call the API and save a token.
    // For this demo, we just simulate it and redirect.
    alert(`Simulating login as: ${role}`);
    router.push('/'); // Redirect to a dashboard/home page
  };

  const roles = [
    { id: 'owner1', name: 'Restaurant Owner 1' },
    { id: 'owner2', name: 'Restaurant Owner 2' },
    { id: 'delivery1', name: 'Delivery Person 1' },
    { id: 'delivery2', name: 'Delivery Person 2' },
    { id: 'customer', name: 'Customer' },
  ];

  return (
    <div className="flex items-center justify-center min-h-screen bg-slate-100">
      <div className="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
        <h2 className="text-2xl font-bold text-center text-gray-900">One-Click Login</h2>
        <p className="text-center text-sm text-gray-600">Select a role to simulate logging in.</p>
        <div className="space-y-3">
          {roles.map(role => (
            <button
              key={role.id}
              onClick={() => handleLogin(role.name)}
              className="w-full px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-md hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors"
            >
              Login as {role.name}
            </button>
          ))}
        </div>
      </div>
    </div>
  );
}