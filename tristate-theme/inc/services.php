<?php
/**
 * The service pages (page-templates/service.php), keyed by page slug.
 *
 * Copy comes from the "TRISTATE SERVICES" document: the first point is the
 * hero lead and the rest become the numbered points. A service without
 * `points` has no copy yet, so its page keeps its current content.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/**
 * Each service: title, heading (h1, with <em>), short name, tag, icon
 * (Tabler name, inc/icons.php), image + alt, badge, lead, points [ title, text ],
 * optional lists [ title, intro, style 'chips'|'tests', items ] and emergency.
 */
function tristate_services() {
	static $services = null;
	if ( null !== $services ) {
		return $services;
	}

	$services = array(
		'emergency-critical-services' => array(
			'title'     => 'Emergency &amp; Critical Care Services',
			'heading'   => 'Emergency &amp; <em>Critical Care</em> Services',
			'short'     => 'Emergency Care',
			'tag'       => '24/7',
			'icon'      => 'ambulance',
			'image'     => 'service-emergency.jpg',
			'alt'       => 'Tristate emergency responders loading a patient into an ambulance',
			'badge'     => 'Open 24/7',
			'emergency' => true,
			'lead'      => 'Tristate Healthcare System provides comprehensive emergency medical services designed to efficiently handle life-threatening situations.',
			'points'    => array(
				array( 'Rapid resuscitation', 'Our clinical team of specialized physicians and nurses are extensively trained in performing rapid resuscitative procedures.' ),
				array( 'Round the clock, with standby ambulances', 'Operating round the clock, our emergency unit works alongside well-equipped standby ambulances to deliver immediate, life-saving care to the sick and injured.' ),
			),
		),

		'cardiovascular-services'     => array(
			'title'   => 'Cardiovascular Services',
			'heading' => '<em>Cardiovascular</em> Services',
			'short'   => 'Cardiovascular',
			'tag'     => 'Flagship',
			'icon'    => 'heartbeat',
			'image'   => 'hero-cardiac.jpg',
			'alt'     => 'Tristate cardiac surgeons operating in theatre',
			'badge'   => 'Our flagship strength',
			'lead'    => 'As our flagship strength, we offer an extensive portfolio of cardiovascular services for both non-invasive and advanced corrective care.',
			'points'  => array(
				array( 'World-renowned specialists', 'Our surgical and medical team comprises world-renowned non-invasive, invasive/interventional cardiologists and cardiothoracic surgeons who maintain exceptional success rates in complex adult and paediatric procedures.' ),
				array( 'A multidisciplinary team', 'Our cardiology unit works hand-in-hand with professional cardiac sonographers and anaesthesiologists to ensure optimal clinical outcomes.' ),
			),
			'lists'   => array(
				array(
					'title' => 'Core surgical <em>procedures</em>',
					'intro' => 'From open-heart surgery to transplants, for adults and children.',
					'style' => 'chips',
					'items' => array(
						'Coronary artery bypass graft (CABG)',
						'Valve replacements',
						'Aneurysm repair',
						'Coronary stent procedures',
						'Pacemaker &amp; defibrillator implantation',
						'Adult &amp; paediatric structural procedures',
						'Pulmonary valvotomy',
						'PDA, ASD &amp; VSD procedures',
						'AV fistula procedures',
						'General thoracic surgery',
						'Congenital cardiac surgery',
						'Heart &amp; lung transplant surgery',
					),
				),
				array(
					'title' => 'Advanced diagnostic <em>testing</em>',
					'intro' => 'Our laboratories and partnership programs provide industry-standard cardiac investigations, including:',
					'style' => 'tests',
					'items' => array(
						array( 'Electrocardiogram', '12-lead ECG' ),
						array( 'Exercise Stress Test', '' ),
						array( 'Holter Monitor', '24-hour ambulatory monitoring' ),
						array( 'Sleep Tests', '' ),
						array( 'Ambulatory Blood Pressure Monitor', 'ABPM' ),
						array( 'Echocardiogram', '' ),
						array( 'Treadmill Stress Echocardiogram', '' ),
					),
				),
			),
		),

		'obstetrics-and-gynaecology'  => array(
			'title'   => 'Obstetrics &amp; Gynaecology',
			'heading' => 'Obstetrics &amp; <em>Gynaecology</em>',
			'short'   => 'Obstetrics &amp; Gynaecology',
			'tag'     => "Women's Health",
			'icon'    => 'gender-female',
			'image'   => 'service-obgyn.jpg',
			'alt'     => 'A Tristate nurse caring for a newborn baby',
			'badge'   => 'Every life stage',
			'lead'    => 'We provide comprehensive, life-stage healthcare for women, spanning routine wellness, childbirth, and transition through menopause, while covering integrated women and child programs.',
			'points'  => array(
				array( 'Preventive &amp; corrective gynaecology', 'Our Gynaecology services encompass preventive care programs such as patient counselling and educational lectures, alongside corrective care sessions for testing, diagnosis, and management of reproductive system disorders.' ),
				array( 'Specialised treatments', 'Specialised gynaecological treatments address menstrual and fertility issues, sexually transmitted infections, and complex hormonal disorders.' ),
				array( 'Complete maternity care', 'Our Obstetrics services deliver complete care for expectant mothers, including structured antenatal monitoring, comprehensive care during childbirth, and postnatal care.' ),
				array( 'High-risk pregnancies', 'Our experienced obstetricians specialise in managing childbirth complications, high-risk pregnancies, and executing clinical interventions and caesarean deliveries.' ),
			),
		),

		'pharmaceutical-services'     => array(
			'title'   => 'Pharmaceutical Services',
			'heading' => '<em>Pharmaceutical</em> Services',
			'short'   => 'Pharmaceutical',
			'tag'     => 'Medication Safety',
			'icon'    => 'pill',
			'image'   => 'service-pharmacy.jpg',
			'alt'     => 'A pharmacist advising a customer at a pharmacy counter',
			'badge'   => 'Accredited suppliers only',
			'lead'    => 'Our fully equipped dispensary stocks all essential medications and surgical consumables supplied strictly by accredited partners and organisations.',
			'points'  => array(
				array( 'Wholesale &amp; retail dispensary', 'We provide both wholesale and retail dispensary services tailored to medical institutions, corporate partners, and individual end-users.' ),
				array( 'Certified pharmacists', 'Our certified and trained pharmacists dispense prescription and over-the-counter (OTC) medications while offering professional counselling on safe prescription usage.' ),
				array( 'Health &amp; wellness screenings', 'Our pharmacy team also conducts health and wellness screenings, including height, weight, and blood pressure checks, while providing expert advice on healthy lifestyles.' ),
			),
		),

		'anaesthesiology'             => array(
			'title'   => 'Anaesthesiology',
			'heading' => '<em>Anaesthesiology</em>',
			'short'   => 'Anaesthesiology',
			'tag'     => 'Perioperative Care',
			'icon'    => 'heart-rate-monitor',
			'image'   => 'service-cardiovascular.jpg',
			'alt'     => 'A Tristate surgical team in theatre',
			'badge'   => 'Before, during &amp; after surgery',
			'lead'    => 'Anaesthesiology at Tristate covers the total perioperative care of patients before, during, and after surgery, integrating anaesthesia, intensive care medicine, critical emergency medicine, and pain management.',
			'points'  => array(
				array( 'Safe from start to finish', 'The foundational core of the speciality is the study and administration of anaesthetics to safely support and monitor a patient’s vital functions throughout the entire perioperative phase.' ),
				array( 'Beyond the operating room', 'Our anaesthesiologists extend their expertise beyond the operating room into pre-hospital emergency care, intensive care unit management, inter-facility transport of critically ill patients, and prehabilitation programs designed to optimise patients for surgery.' ),
				array( 'An experienced team', 'The anaesthesia department is staffed by experienced physician anaesthesiologists, certified registered nurse anaesthetists (CRNA), and certified anaesthesiologist assistants (CAA).' ),
			),
		),

		'outpatient-department'       => array(
			'title'   => 'Outpatient Department (OPD)',
			'heading' => 'Outpatient <em>Department</em>',
			'short'   => 'Outpatient',
			'tag'     => 'Consultations',
			'icon'    => 'stethoscope',
			'image'   => 'service-outpatient.jpg',
			'alt'     => 'A doctor in consultation with a patient',
			'badge'   => 'No overnight stay',
			'lead'    => 'Designed for convenience, many specialised procedures and diagnostic tests can be performed within hours without requiring an overnight hospital stay.',
			'points'  => array(
				array( 'A full spectrum of care', 'Our outpatient facilities offer a comprehensive spectrum of care encompassing corrective treatments, preventive diagnostics, and wellness programs.' ),
				array( 'From diagnosis to rehabilitation', 'Outpatient services cover diagnosis, medical observation, specialist consultation, active treatment, intervention, and rehabilitation services.' ),
				array( 'Certified specialists, modern facilities', 'All procedures are executed using state-of-the-art facilities by certified specialists within a conducive, healing environment, incorporating advanced medical technology even outside traditional wards.' ),
			),
		),

		'education-research'          => array(
			'title'   => 'Education &amp; Research',
			'heading' => 'Education &amp; <em>Research</em>',
			'short'   => 'Education &amp; Research',
			'tag'     => 'Innovation',
			'icon'    => 'school',
			'image'   => 'service-education.jpg',
			'alt'     => 'A senior doctor reviewing a scan with a colleague',
			'badge'   => 'Advancing standards',
			'lead'    => 'Tristate remains deeply committed to discovering innovative pathways to deliver world-class, accessible healthcare across Nigeria.',
			'points'  => array(
				array( 'Research partnerships', 'We actively collaborate with renowned research institutes and medical professionals to research, master, and implement advanced methods of restoring patients to optimal health.' ),
				array( 'Physician-led training', 'Our physician-led training methodology focuses on sharing and imparting clinical knowledge to resident doctors through our dedicated research and training institutes.' ),
				array( 'Setting the pace', 'Through these efforts, we aim to cement our legacy as pacesetters in the provision of accessible, high-quality healthcare.' ),
			),
		),

		'dialysis'                    => array(
			'title'   => 'Dialysis',
			'heading' => '<em>Dialysis</em>',
			'short'   => 'Dialysis',
			'tag'     => 'Kidney Care',
			'icon'    => 'droplet',
			'image'   => 'service-dialysis.jpg',
			'alt'     => 'Treatment chairs and equipment in a hospital treatment room',
			'badge'   => 'Short or long-term care',
			'lead'    => 'Dialysis is a specialised medical procedure wherein advanced machinery takes over the filtration functions of the kidneys to remove metabolic waste and excess water from the body.',
			'points'  => array(
				array( 'For days, weeks or years', 'This critical intervention helps maintain proper fluid and electrolyte balance and can be administered temporarily (over days or weeks) or managed long-term across years.' ),
				array( 'A dedicated department', 'Our dedicated dialysis department is operated by highly trained clinical professionals utilising state-of-the-art equipment.' ),
			),
		),

		'respiratory-critical-care'   => array(
			'title'   => 'Respiratory &amp; Critical Care',
			'heading' => 'Respiratory &amp; <em>Critical Care</em>',
			'short'   => 'Respiratory Care',
			'tag'     => 'Intensive Care',
			'icon'    => 'lungs',
			'image'   => 'service-critical-care.jpg',
			'alt'     => 'Clinicians speaking with a patient on a hospital ward',
			'badge'   => 'Families kept informed',
			'lead'    => 'Critical care patients present with complex, life-threatening conditions, requiring our dedicated staff to deploy a multidisciplinary, well-rounded care system focused on restoring optimal health.',
			'points'  => array(
				array( 'Regular rounds, personal care', 'Regular patient rounds and highly personalised care are prioritised within our Critical Care Units (CCUs).' ),
				array( 'Families kept in the loop', 'We maintain a seamless, 360-degree communication line between our specialist physicians and patient families throughout the entire duration of the care process.' ),
			),
		),

		'radiology'                   => array(
			'title'   => 'Radiology',
			'heading' => '<em>Radiology</em>',
			'short'   => 'Radiology',
			'tag'     => 'Imaging',
			'icon'    => 'scan',
			'image'   => 'service-imaging.jpg',
			'alt'     => 'A clinician reviewing a chest X-ray',
			'badge'   => 'Digital imaging',
			'lead'    => 'Our Radiology Unit features cutting-edge digital imaging technology, operated by highly trained technologists and certified radiologists committed to complete diagnostic excellence.',
			'points'  => array(
				array( 'A complete imaging suite', 'We offer a comprehensive suite of diagnostic imaging tests, including X-rays, CT scans, MRIs, and specialized breast imaging, prioritizing patient safety and comfort at every step.' ),
				array( 'Interventional radiology', 'In addition to diagnostics, we partner with renowned interventional radiologists to perform complex minimally invasive procedures such as angioplasty, stent placements, needle biopsies, and radiofrequency ablation.' ),
			),
		),

		// No copy yet: listed among the other services, but the page keeps its own content.
		'laboratory-services'         => array(
			'title' => 'Laboratory Services',
			'short' => 'Laboratory',
			'tag'   => 'Diagnostics',
			'icon'  => 'flask',
		),
	);

	return $services;
}

/** The service shown on this page slug, or null when it has no copy yet. */
function tristate_service( $slug ) {
	$services = tristate_services();
	return empty( $services[ $slug ]['points'] ) ? null : $services[ $slug ];
}

/** Slugs of the services that have their own designed page. */
function tristate_service_slugs() {
	return array_keys( array_filter( tristate_services(), static function ( $service ) {
		return ! empty( $service['points'] );
	} ) );
}
