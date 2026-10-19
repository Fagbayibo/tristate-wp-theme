<?php
/**
 * Services: the 15 services from the client's content brief ("Tristate
 * Hospitals Website Content", Oct 2026), grouped into areas of care.
 *
 * Every service has its own page (page-templates/service.php, routed by slug),
 * a place in the grouped menu and the A–Z directory, and, when it can be
 * booked, an option in the appointment form.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/**
 * Areas of care, in menu order: label, one-line summary (homepage), icon,
 * image for the Our Services page, and the service slugs in each.
 * `also` lists services that belong to another group but are cross-linked here.
 */
function tristate_service_groups() {
	return array(
		'medical'      => array(
			'label'    => 'Medical Care and Wellness',
			'summary'  => 'New concerns, ongoing conditions and preventive health reviews.',
			'icon'     => 'stethoscope',
			'image'    => 'service-outpatient.jpg',
			'alt'      => 'A doctor in consultation with a patient',
			'services' => array( 'family-medicine', 'internal-medicine', 'wellness' ),
		),
		'heart'        => array(
			'label'    => 'Heart and Chest Care',
			'summary'  => 'Cardiology and Cardiothoracic Surgery.',
			'icon'     => 'heartbeat',
			'image'    => 'hero-cardiac.jpg',
			'alt'      => 'Tristate cardiac surgeons operating in theatre',
			'services' => array( 'cardiovascular-services', 'cardiothoracic-surgery' ),
		),
		'surgery'      => array(
			'label'    => 'Surgery',
			'summary'  => 'General Surgery, Neurosurgery and Orthopaedics.',
			'icon'     => 'medical-cross',
			'image'    => 'service-cardiovascular.jpg',
			'alt'      => 'A Tristate surgical team in theatre',
			'services' => array( 'general-surgery', 'neurosurgery', 'orthopaedics' ),
			'also'     => array( 'cardiothoracic-surgery' ),
		),
		'womens'       => array(
			'label'    => 'Women’s Health',
			'summary'  => 'Gynaecological concerns and pregnancy care.',
			'icon'     => 'gender-female',
			'image'    => 'service-obgyn.jpg',
			'alt'      => 'A Tristate nurse caring for a newborn baby',
			'services' => array( 'obstetrics-and-gynaecology' ),
		),
		'emergency'    => array(
			'label'    => 'Emergency and Critical Care',
			'summary'  => 'Urgent assessment and intensive care.',
			'icon'     => 'ambulance',
			'image'    => 'service-emergency.jpg',
			'alt'      => 'Tristate emergency responders loading a patient into an ambulance',
			'services' => array( 'emergency-critical-services', 'intensive-care-unit' ),
		),
		'diagnostics'  => array(
			'label'    => 'Diagnostics and Procedures',
			'summary'  => 'Radiology and Endoscopy.',
			'icon'     => 'scan',
			'image'    => 'service-imaging.jpg',
			'alt'      => 'A clinician reviewing a chest X-ray',
			'services' => array( 'radiology', 'endoscopy' ),
		),
		'regenerative' => array(
			'label'    => 'Regenerative Medicine',
			'summary'  => 'Advice on available treatments and suitability.',
			'icon'     => 'dna-2',
			'image'    => 'service-laboratory.jpg',
			'alt'      => 'A Tristate laboratory scientist at work',
			'services' => array( 'regenerative-medicine' ),
		),
		'virtual'      => array(
			'label'    => 'Virtual Consultations',
			'summary'  => 'Another way to access suitable appointments and follow-up.',
			'icon'     => 'video',
			'image'    => 'why-1.jpg',
			'alt'      => 'A smiling Tristate doctor with a stethoscope',
			'services' => array( 'virtual-consultations' ),
		),
	);
}

/**
 * Each service, keyed by page slug:
 * - title    name in menus, lists and the form ("Cardiology")
 * - group    key in tristate_service_groups()
 * - icon     Tabler icon (inc/icons.php)
 * - image    hero photo + alt
 * - eyebrow  optional label above the h1 (defaults to the group)
 * - heading  optional h1 (defaults to the title); <em> marks the accent
 * - intro    paragraphs under the h1
 * - cta      [ label, action ]: 'book' (appointment form, this service chosen),
 *            'virtual' (form, virtual chosen), 'hospitals' (Our Hospitals page)
 * - book     false when the service cannot be requested through the form
 * - sections optional body: title, id, paras, tests [ name, text, id ],
 *            defs [ term, text ] (the term runs into its text), cta, link (a slug)
 */
function tristate_services() {
	static $services = null;
	if ( null !== $services ) {
		return $services;
	}

	$services = array(
		'family-medicine'             => array(
			'title' => 'Family Medicine',
			'group' => 'medical',
			'icon'  => 'stethoscope',
			'image' => 'service-outpatient.jpg',
			'alt'   => 'A doctor in consultation with a patient',
			'intro' => array( 'You do not need to know what is wrong before asking for help. Start here for new symptoms, routine reviews or ongoing health concerns. Your clinician considers your history and daily life, recommends any necessary tests and helps identify when specialist care is needed.' ),
			'cta'   => array( 'Request a Family Medicine Appointment', 'book' ),
		),

		'internal-medicine'           => array(
			'title' => 'Internal Medicine',
			'group' => 'medical',
			'icon'  => 'report-medical',
			'image' => 'intro-2.jpg',
			'alt'   => 'A Tristate consultant at her desk',
			'intro' => array( 'When symptoms persist or you are managing several health conditions, it helps to consider them together. Our Internal Medicine service assesses adult medical concerns, reviewing your symptoms, medicines and previous results to guide treatment and follow-up.' ),
			'cta'   => array( 'Request an Internal Medicine Appointment', 'book' ),
		),

		'wellness'                    => array(
			'title' => 'Wellness',
			'group' => 'medical',
			'icon'  => 'leaf',
			'image' => 'why-2.jpg',
			'alt'   => 'A doctor checking an elderly patient’s blood pressure',
			'intro' => array( 'Make time for your health, even when you feel well. A wellness consultation explores your health history, risks and goals, with advice on prevention and screening appropriate to you. Ask which checks would be useful and why.' ),
			'cta'   => array( 'Plan a Wellness Visit', 'book' ),
		),

		'cardiovascular-services'     => array(
			'title'    => 'Cardiology',
			'group'    => 'heart',
			'icon'     => 'heartbeat',
			'image'    => 'hero-cardiac.jpg',
			'alt'      => 'Tristate cardiac surgeons operating in theatre',
			'eyebrow'  => 'Advanced Cardiovascular Services',
			'heading'  => 'Care for the Heart <em>From The Heart</em>',
			'intro'    => array(
				'A heart concern can bring questions about your health, your family and the life you want to return to. At Tristate, advanced cardiovascular care begins with understanding those concerns alongside your medical needs.',
				'Our cardiology and cardiothoracic teams assess your condition and discuss the investigations or treatment appropriate to you. You should understand why a recommendation is made, what the alternatives are and what it may mean for daily life.',
			),
			'cta'      => array( 'Request a Heart Consultation', 'book' ),
			'sections' => array(
				array(
					'id'    => 'consultation',
					'title' => 'Your consultation',
					'paras' => array( 'Tell us about your symptoms, existing conditions and medicines. Bring previous results where available. Whether you are reviewing your heart health or discussing an established diagnosis, your consultation helps determine what needs attention.' ),
				),
				array(
					'id'    => 'heart-tests',
					'title' => 'Understand your heart tests',
					'tests' => array(
						array( 'ECG or EKG', 'A short recording of the heart’s electrical activity that provides information about its rate and rhythm. ECG and EKG are names for the same test.', 'ecg' ),
						array( 'Echocardiogram', 'An ultrasound examination that shows the heart’s structure, pumping function and valves. It answers different questions from an ECG.', 'echocardiogram' ),
						array( 'Holter monitoring', 'A portable monitor records your heart rhythm over time to help investigate changes that may not appear during a brief test.', 'holter-monitoring' ),
						array( 'Exercise stress test', 'Monitors how your heart responds to supervised exercise. The team will explain how to prepare and what the examination involves.', 'exercise-stress-test' ),
						array( 'Specialist echocardiography', 'Some assessments need a closer view through transoesophageal echocardiography or an examination of heart function under stress. Your clinician will explain why a particular test is recommended.', 'specialist-echocardiography' ),
					),
					'cta'   => array( 'Enquire About a Heart Test', 'book' ),
				),
				array(
					'id'    => 'before-and-after',
					'title' => 'Before and after your test',
					'paras' => array( 'Confirm any preparation instructions, when the results will be ready and who will discuss them with you. Do not change your medicines unless your clinical team advises you to do so.' ),
				),
				array(
					'id'    => 'treatment',
					'kicker' => 'Cardiovascular treatment and recovery',
					'title' => 'If a procedure is recommended',
					'paras' => array( 'Understanding your choices is part of preparing for treatment. Ask what the procedure aims to achieve, its risks, the alternatives and the support you may need afterwards.' ),
				),
				array(
					'id'    => 'cath-lab',
					'title' => 'Cath lab and interventional care',
					'paras' => array( 'The cardiac catheterisation laboratory, or cath lab, supports investigations and treatments using thin tubes guided through blood vessels.' ),
					'defs'  => array(
						array( 'Coronary angiography', 'uses contrast dye and X-ray imaging to examine the arteries supplying the heart.', 'coronary-angiography' ),
						array( 'Cardiac catheterisation', 'assesses pressures and other aspects of heart function. Right- and left-heart assessments answer different questions.', 'cardiac-catheterisation' ),
						array( 'Coronary angioplasty and stenting', 'opens a narrowed coronary artery, usually with a balloon and a small supporting tube called a stent.', 'angioplasty' ),
					),
					'cta'   => array( 'Discuss a Cath Lab Referral', 'book' ),
				),
				array(
					'id'    => 'rhythm-and-devices',
					'title' => 'Heart rhythm and device care',
					'defs'  => array(
						array( 'Pacemaker assessment and implantation', 'evaluates whether a device could help with certain slow heart rhythms or electrical conduction problems.', 'pacemaker' ),
						array( 'Pacemaker follow-up', 'checks how the device is working and reviews its settings.', 'pacemaker-follow-up' ),
						array( 'Cardiac resynchronisation therapy', 'a specialised pacing treatment that helps the heart’s chambers work together more effectively in selected patients.', 'cardiac-resynchronisation-therapy' ),
					),
				),
				array(
					'id'    => 'structural-heart',
					'title' => 'Structural heart procedures',
					'defs'  => array(
						array( 'Balloon valvuloplasty', 'widens a narrowed heart valve in suitable cases.', 'balloon-valvuloplasty' ),
						array( 'Atrial septal defect closure', 'closes a suitable opening between the heart’s upper chambers using a catheter-based device.', 'asd-closure' ),
						array( 'Patent ductus arteriosus closure', 'treats a persistent connection between major blood vessels near the heart when catheter-based closure is appropriate.', 'pda-closure' ),
					),
				),
				array(
					'id'    => 'cardiothoracic-surgery',
					'title' => 'Cardiothoracic Surgery',
					'link'  => 'cardiothoracic-surgery',
				),
				array(
					'id'    => 'after-treatment',
					'title' => 'Preparing for life after treatment',
					'paras' => array( 'Discuss your medicines, return to daily activities and follow-up appointments before leaving hospital. Ask about rehabilitation and other recovery support, and let the team know if travel, cost or family responsibilities could affect your care plan.' ),
				),
			),
		),

		'cardiothoracic-surgery'      => array(
			'title' => 'Cardiothoracic Surgery',
			'group' => 'heart',
			'icon'  => 'lungs',
			'image' => 'mission.jpg',
			'alt'   => 'A Tristate surgeon in scrubs standing in an operating theatre',
			'intro' => array(
				'Consultations for conditions affecting the heart and chest that may need surgery. Cardiac procedures include coronary artery bypass surgery, heart valve repair or replacement, and surgery for selected congenital heart conditions.',
				'Your surgeon will explain the proposed operation, alternatives and recovery considerations relevant to you.',
			),
			'cta'   => array( 'Request a Surgical Consultation', 'book' ),
		),

		'general-surgery'             => array(
			'title' => 'General Surgery',
			'group' => 'surgery',
			'icon'  => 'medical-cross',
			'image' => 'service-cardiovascular.jpg',
			'alt'   => 'A Tristate surgical team in theatre',
			'intro' => array( 'A surgical referral is a chance to understand your options. Your surgeon reviews your symptoms and investigations, explains whether an operation is recommended and discusses alternatives, preparation and recovery.' ),
			'cta'   => array( 'Request a General Surgery Consultation', 'book' ),
		),

		'neurosurgery'                => array(
			'title' => 'Neurosurgery',
			'group' => 'surgery',
			'icon'  => 'brain',
			'image' => 'service-education.jpg',
			'alt'   => 'A senior doctor reviewing a scan with a colleague',
			'intro' => array(
				'Brain, spine and nerve concerns can affect movement, independence and daily life. A neurosurgical consultation reviews your symptoms and scans to assess whether surgical treatment should be considered.',
				'Bring relevant reports and tell the specialist what has changed and what you find difficult.',
			),
			'cta'   => array( 'Request a Neurosurgical Consultation', 'book' ),
		),

		'orthopaedics'                => array(
			'title' => 'Orthopaedics',
			'group' => 'surgery',
			'icon'  => 'bone',
			'image' => 'intro-3.jpg',
			'alt'   => 'A Tristate clinician with a stethoscope',
			'intro' => array(
				'Walking, working or getting a good night’s sleep can become difficult when bones or joints hurt. Our Orthopaedics service assesses bone, joint and related muscle and tendon problems, with your comfort and ability to move in view.',
				'Discuss the treatment options, including surgery where appropriate, and what each could mean for your daily activities.',
			),
			'cta'   => array( 'Request an Orthopaedic Consultation', 'book' ),
		),

		'obstetrics-and-gynaecology'  => array(
			'title' => 'Obstetrics and Gynaecology',
			'group' => 'womens',
			'icon'  => 'gender-female',
			'image' => 'service-obgyn.jpg',
			'alt'   => 'A Tristate nurse caring for a newborn baby',
			'intro' => array(
				'Some health concerns are personal or difficult to discuss. Whether you need advice about a gynaecological concern or care during pregnancy, your consultation is a place to explain what you are experiencing and ask questions.',
				'Your clinician will discuss any recommended examination or test. Bring relevant reports and, if pregnant, your existing pregnancy records.',
			),
			'cta'   => array( 'Request a Women’s Health Appointment', 'book' ),
		),

		'emergency-critical-services' => array(
			'title'     => 'Emergency Care',
			'group'     => 'emergency',
			'icon'      => 'ambulance',
			'image'     => 'service-emergency.jpg',
			'alt'       => 'Tristate emergency responders loading a patient into an ambulance',
			'emergency' => true,
			'book'      => false,
			'intro'     => array(
				'When illness or injury needs urgent attention, the first step is assessment of how quickly care is needed. Treatment may involve further tests, admission or transfer, depending on the patient’s condition.',
				'For a medical emergency, seek immediate care at the nearest emergency department. Do not wait for an online appointment or message reply.',
			),
			'cta'       => array( 'Emergency Locations and Contact Details', 'hospitals' ),
		),

		'intensive-care-unit'         => array(
			'title' => 'Intensive Care Unit',
			'group' => 'emergency',
			'icon'  => 'bed',
			'image' => 'service-critical-care.jpg',
			'alt'   => 'Clinicians speaking with a patient on a hospital ward',
			'book'  => false, // Admission is arranged by the treating team.
			'intro' => array(
				'When someone you love is critically ill, clear information matters. The ICU cares for patients who need intensive monitoring and specialist treatment. Admission is arranged by the treating team according to clinical need.',
				'Ask who will update your family, when to expect the next conversation and what the visiting arrangements are.',
			),
			'cta'   => array( 'ICU Information for Families', 'hospitals' ),
		),

		'radiology'                   => array(
			'title' => 'Radiology',
			'group' => 'diagnostics',
			'icon'  => 'scan',
			'image' => 'service-imaging.jpg',
			'alt'   => 'A clinician reviewing a chest X-ray',
			'intro' => array( 'Medical imaging helps your clinician investigate symptoms or review a condition. Share your referral and previous relevant reports when booking. The team will confirm the examination, preparation and how your clinician receives the results.' ),
			'cta'   => array( 'Enquire About Radiology', 'book' ),
		),

		'endoscopy'                   => array(
			'title' => 'Endoscopy',
			'group' => 'diagnostics',
			'icon'  => 'camera',
			'image' => 'service-radiology.jpg',
			'alt'   => 'Tristate clinicians reviewing findings together',
			'intro' => array( 'Endoscopy uses a camera-equipped instrument to examine inside the body. Before your appointment, confirm what the procedure involves, how to prepare and whether someone should accompany you home.' ),
			'cta'   => array( 'Enquire About Endoscopy', 'book' ),
		),

		'regenerative-medicine'       => array(
			'title'   => 'Regenerative Medicine',
			'group'   => 'regenerative',
			'icon'    => 'dna-2',
			'image'   => 'service-laboratory.jpg',
			'alt'     => 'A Tristate laboratory scientist at work',
			'heading' => 'Explore your options with <em>clear information</em>',
			'intro'   => array(
				'If you are considering regenerative medicine, start with an assessment of your condition and a discussion of the treatments available at Tristate.',
				'Ask what the evidence shows, what remains uncertain and how the proposed approach compares with other options. Potential benefits, risks, costs and follow-up should all be part of that discussion.',
			),
			'cta'     => array( 'Enquire About Regenerative Medicine', 'book' ),
		),

		'virtual-consultations'       => array(
			'title'   => 'Virtual Consultations',
			'group'   => 'virtual',
			'icon'    => 'video',
			'image'   => 'why-1.jpg',
			'alt'     => 'A smiling Tristate doctor with a stethoscope',
			'heading' => 'A consultation <em>without the journey</em>',
			'book'    => false, // A way to be seen, chosen in the form's "where" question.
			'intro'   => array(
				'Some health discussions and follow-up appointments can take place remotely. Our team will confirm whether a virtual consultation is suitable and explain the fees and connection details.',
				'Choose a private space and have your medicines list and relevant reports ready. If an examination, test or procedure is needed, your clinician may recommend an in-person visit.',
				'Virtual consultations are not for emergencies.',
			),
			'cta'     => array( 'Request a Virtual Consultation', 'virtual' ),
		),
	);

	return $services;
}

/** One service by page slug, or null. */
function tristate_service( $slug ) {
	$services = tristate_services();
	return isset( $services[ $slug ] ) ? $services[ $slug ] : null;
}

/** Slugs of every service page. */
function tristate_service_slugs() {
	return array_keys( tristate_services() );
}

/** A service page's URL. */
function tristate_service_url( $slug, $anchor = '' ) {
	return home_url( "/$slug/" ) . ( $anchor ? "#$anchor" : '' );
}

/** The group a service belongs to: [ key, group ]. */
function tristate_service_group( $slug ) {
	$service = tristate_service( $slug );
	$groups  = tristate_service_groups();
	return $service ? array( $service['group'], $groups[ $service['group'] ] ) : array( '', null );
}

/** Services that can be requested through the appointment form: slug => name. */
function tristate_bookable_services() {
	$bookable = array();
	foreach ( tristate_services() as $slug => $service ) {
		if ( ! isset( $service['book'] ) || false !== $service['book'] ) {
			$bookable[ $slug ] = $service['title'];
		}
	}
	return $bookable;
}

/**
 * Where a service's call to action goes. 'book' opens the homepage form with
 * this service chosen; 'virtual' with a virtual consultation chosen.
 */
function tristate_cta_url( $action, $slug = '' ) {
	switch ( $action ) {
		case 'book':
			return add_query_arg( 'service', $slug, home_url( '/' ) ) . '#appointment';
		case 'virtual':
			return add_query_arg( 'visit', 'virtual', home_url( '/' ) ) . '#appointment';
		case 'hospitals':
			return home_url( '/our-hospitals/' );
	}
	return home_url( '/' );
}

/**
 * The A–Z directory: every service, every heart test and procedure, and the
 * other names people search for. Each entry: [ name, url ]. ECG and EKG lead
 * to the same entry, as the brief asks.
 */
function tristate_az_entries() {
	$entries = array();
	foreach ( tristate_services() as $slug => $service ) {
		$entries[] = array( $service['title'], tristate_service_url( $slug ) );
		foreach ( isset( $service['sections'] ) ? $service['sections'] : array() as $section ) {
			foreach ( array_merge( isset( $section['tests'] ) ? $section['tests'] : array(), isset( $section['defs'] ) ? $section['defs'] : array() ) as $item ) {
				if ( 'ECG or EKG' === $item[0] ) {
					$entries[] = array( 'ECG', tristate_service_url( $slug, $item[2] ) );
					$entries[] = array( 'EKG', tristate_service_url( $slug, $item[2] ) );
					continue;
				}
				$entries[] = array( $item[0], tristate_service_url( $slug, $item[2] ) );
			}
		}
	}
	$aliases = array(
		'Advanced Cardiovascular Services' => tristate_service_url( 'cardiovascular-services' ),
		'Gynaecology'                      => tristate_service_url( 'obstetrics-and-gynaecology' ),
		'Obstetrics'                       => tristate_service_url( 'obstetrics-and-gynaecology' ),
		'ICU'                              => tristate_service_url( 'intensive-care-unit' ),
		'Imaging'                          => tristate_service_url( 'radiology' ),
		'Pregnancy care'                   => tristate_service_url( 'obstetrics-and-gynaecology' ),
	);
	foreach ( $aliases as $name => $url ) {
		$entries[] = array( $name, $url );
	}

	usort( $entries, static function ( $a, $b ) {
		return strcasecmp( $a[0], $b[0] );
	} );
	return $entries;
}

/**
 * Service pages that no longer exist, and where their old links now go.
 * Followed with a 301 (inc/pages.php).
 */
function tristate_retired_pages() {
	return array(
		'outpatient-department'     => 'family-medicine',
		'respiratory-critical-care' => 'intensive-care-unit',
		'education-research'        => 'about-us',
		'pharmaceutical-services'   => 'our-services',
		'anaesthesiology'           => 'our-services',
		'dialysis'                  => 'our-services',
		'laboratory-services'       => 'our-services',
		'contact-us'                => 'our-hospitals',
	);
}
