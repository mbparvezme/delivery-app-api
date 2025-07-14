import Link from 'next/link';

export default function RegisterPage() {
  return (
    <div className="flex items-center justify-center min-h-screen bg-slate-100">
      <div className="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
        <h2 className="text-2xl font-bold text-center text-gray-900">Create an Account</h2>
        <p className="text-center text-sm text-gray-600">This is a placeholder registration page. Functionality is not implemented.</p>
        <form className="mt-8 space-y-6" onSubmit={(e) => e.preventDefault()}>
          <div>
            <label htmlFor="name" className="sr-only">Name</label>
            <input id="name" name="name" type="text" required className="w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500" placeholder="Full Name" disabled />
          </div>
          <div>
            <label htmlFor="email-address" className="sr-only">Email address</label>
            <input id="email-address" name="email" type="email" required className="w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500" placeholder="Email address" disabled />
          </div>
          <div>
            <label htmlFor="password" className="sr-only">Password</label>
            <input id="password" name="password" type="password" required className="w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500" placeholder="Password" disabled />
          </div>
          <div>
            <button type="submit" className="w-full px-4 py-2 font-medium text-white bg-sky-600 rounded-md hover:bg-sky-700 disabled:bg-gray-400" disabled>
              Sign up
            </button>
          </div>
        </form>
        <div className="text-sm text-center">
          <Link href="/login" className="font-medium text-sky-600 hover:text-sky-500">
            Already have an account? Sign in
          </Link>
        </div>
      </div>
    </div>
  );
}