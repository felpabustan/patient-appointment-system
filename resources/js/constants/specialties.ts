export const specialtyGroups = {
    'Primary Care': [
      'Family Medicine',
      'Internal Medicine',
      'Pediatrics',
      'General Practice',
    ],
    'Medical Specialties': [
      'Cardiology',
      'Dermatology',
      'Endocrinology',
      'Gastroenterology',
      'Neurology',
      'Oncology',
      'Pulmonology',
      'Rheumatology',
    ],
    'Surgical Specialties': [
      'General Surgery',
      'Orthopedic Surgery',
      'Neurosurgery',
      'Cardiac Surgery',
      'Plastic Surgery',
      'Vascular Surgery',
    ],
    'Other Specialties': [
      'Anesthesiology',
      'Emergency Medicine',
      'Obstetrics and Gynecology',
      'Ophthalmology',
      'Psychiatry',
      'Radiology',
      'Urology',
    ],
  } as const
  
  // Helper type for getting all specialties as a union type
  export type Specialty = typeof specialtyGroups[keyof typeof specialtyGroups][number]